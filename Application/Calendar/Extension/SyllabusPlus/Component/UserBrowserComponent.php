<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Core\User\Component\UserSettingsComponent;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Calendar\Renderer\Form\JumpForm;
use Chamilo\Libraries\Calendar\Renderer\Legend;
use Chamilo\Libraries\Calendar\Renderer\Type\View\MiniMonthRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRendererFactory;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Glyph\BootstrapGlyph;
use Chamilo\Libraries\Format\Structure\Glyph\FontAwesomeGlyph;
use Chamilo\Libraries\Format\Structure\Page;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\LocalSetting;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\UserCalendarRendererProvider;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserBrowserComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @var JumpForm
     */
    private $form;

    /**
     *
     * @var \Chamilo\Libraries\Calendar\Renderer\Type\View\MiniMonthRenderer
     */
    private $miniMonthRenderer;

    private $viewRenderer;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->checkAuthorization();
        $this->initialize();

        BreadcrumbTrail::get_instance()->add(
            new Breadcrumb(
                null,
                $this->getUserCalendar()->fullname(
                    $this->getUserCalendar()->get_lastname(),
                    $this->getUserCalendar()->get_firstname())));

        return $this->renderCalendar();
    }

    /**
     *
     * @return string
     */
    protected function renderCalendar()
    {
        $html = array();

        $html[] = $this->render_header();

        $html[] = '<div class="row">';
        $html[] = $this->getViewRenderer()->render();
        $html[] = '</div>';

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    protected function initialize()
    {
        $header = Page::getInstance()->getHeader();
        $header->addCssFile(
            Theme::getInstance()->getCssPath(\Chamilo\Application\Calendar\Manager::package(), true) . 'Print.css',
            'print');

        if ($this->getJumpForm()->validate())
        {
            $this->setCurrentRendererTime($this->getJumpForm()->getTime());
        }

        $this->set_parameter(ViewRenderer::PARAM_TYPE, $this->getCurrentRendererType());
        $this->set_parameter(ViewRenderer::PARAM_TIME, $this->getCurrentRendererTime());
    }

    /**
     *
     * @return \Chamilo\Libraries\Calendar\Renderer\Form\JumpForm
     */
    protected function getJumpForm()
    {
        if (! isset($this->form))
        {
            $this->form = new JumpForm($this->get_url($this->getDisplayParameters()), $this->getCurrentRendererTime());
        }

        return $this->form;
    }

    /**
     *
     * @return string[]
     */
    protected function getDisplayParameters()
    {
        return array(
            self::PARAM_CONTEXT => self::package(),
            self::PARAM_ACTION => self::ACTION_USER_BROWSER,
            ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
            self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id());
    }

    /**
     *
     * @return string
     */
    protected function renderContent()
    {
        $html = array();

        $html[] = '<div class="mini_calendar">';
        $html[] = $this->renderSidebar();

        $html[] = '</div>';
        $html[] = '<div class="normal_calendar">';
        $html[] = $this->getViewRenderer()->render();

        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return \Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer
     */
    protected function getViewRenderer()
    {
        if (! isset($this->viewRenderer))
        {
            $dataProvider = $this->getCalendarDataProvider();
            $calendarLegend = new Legend($dataProvider);

            $rendererFactory = new ViewRendererFactory(
                $this->getCurrentRendererType(),
                $dataProvider,
                $calendarLegend,
                $this->getCurrentRendererTime(),
                $this->getViewActions());
            $renderer = $rendererFactory->getRenderer();

            if ($this->getCurrentRendererType() == ViewRenderer::TYPE_DAY ||
                 $this->getCurrentRendererType() == ViewRenderer::TYPE_WEEK)
            {
                $renderer->setStartHour(
                    LocalSetting::getInstance()->get('working_hours_start', 'Chamilo\Libraries\Calendar'));
                $renderer->setEndHour(
                    LocalSetting::getInstance()->get('working_hours_end', 'Chamilo\Libraries\Calendar'));
                $renderer->setHideOtherHours(
                    LocalSetting::getInstance()->get('hide_non_working_hours', 'Chamilo\Libraries\Calendar'));
            }

            $this->viewRenderer = $renderer;
        }

        return $this->viewRenderer;
    }

    protected function getViewActions()
    {
        $actions = array();

        $generalButtonGroup = new ButtonGroup();

        $printUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_PRINT,
                ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
                ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id()));

        $generalButtonGroup->addButton(
            new Button(
                Translation::get(self::ACTION_PRINT . 'Component'),
                new BootstrapGlyph('print'),
                $printUrl->getUrl()));

        $iCalUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId()));

        $generalButtonGroup->addButton(
            new Button(Translation::get('ICalExternal'), new BootstrapGlyph('globe'), $iCalUrl->getUrl()));

        $settingsUrl = new Redirect(
            array(
                Application::PARAM_CONTEXT => \Chamilo\Core\User\Manager::context(),
                Application::PARAM_ACTION => \Chamilo\Core\User\Manager::ACTION_USER_SETTINGS,
                UserSettingsComponent::PARAM_CONTEXT => 'Chamilo\Libraries\Calendar'));

        $generalButtonGroup->addButton(
            new Button(Translation::get('ConfigComponent'), new BootstrapGlyph('cog'), $settingsUrl->getUrl()));

        $actions[] = $generalButtonGroup;

        $browserButtonGroup = new ButtonGroup();

        $userUrl = new Redirect(array(self::PARAM_CONTEXT => self::package(), self::PARAM_ACTION => self::ACTION_USER));

        $browserButtonGroup->addButton(
            new Button(
                Translation::get(self::ACTION_USER . 'Component'),
                new FontAwesomeGlyph('user'),
                $userUrl->getUrl()));

        $groupUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_GROUP_BROWSER,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId()));

        $browserButtonGroup->addButton(
            new Button(
                Translation::get(self::ACTION_GROUP_BROWSER . 'Component'),
                new FontAwesomeGlyph('users'),
                $groupUrl->getUrl()));

        $locationUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_LOCATION_BROWSER,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId()));

        $browserButtonGroup->addButton(
            new Button(
                Translation::get(self::ACTION_LOCATION_BROWSER . 'Component'),
                new FontAwesomeGlyph('map-marker'),
                $locationUrl->getUrl()));

        $actions[] = $browserButtonGroup;

        return $actions;
    }

    protected function getMiniMonthRenderer()
    {
        if (! isset($this->miniMonthRenderer))
        {
            $dataProvider = $this->getCalendarDataProvider();
            $calendarLegend = new Legend($dataProvider);

            $miniMonthRenderer = new MiniMonthRenderer(
                $dataProvider,
                $calendarLegend,
                $this->getCurrentRendererTime(),
                null,
                $this->getMiniMonthMarkPeriod());

            $this->miniMonthRenderer = $miniMonthRenderer;
        }

        return $this->miniMonthRenderer;
    }

    /**
     *
     * @return string
     */
    protected function renderSidebar()
    {
        $html = array();

        $html[] = $this->getMiniMonthRenderer()->render();
        $html[] = $this->getJumpForm()->toHtml();

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider
     */
    protected function getCalendarDataProvider()
    {
        if (! isset($this->calendarDataProvider))
        {
            $this->calendarDataProvider = new UserCalendarRendererProvider(
                $this->getUserCalendar(),
                $this->get_user(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }
}
