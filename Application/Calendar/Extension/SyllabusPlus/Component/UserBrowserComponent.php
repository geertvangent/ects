<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Core\User\Component\UserSettingsComponent;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Calendar\Renderer\Form\JumpForm;
use Chamilo\Libraries\Calendar\Renderer\Legend;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRendererFactory;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ActionBar\DropdownButton;
use Chamilo\Libraries\Format\Structure\ActionBar\SubButton;
use Chamilo\Libraries\Format\Structure\ActionBar\SubButtonDivider;
use Chamilo\Libraries\Format\Structure\ActionBar\SubButtonHeader;
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
        if ($this->isPrintRequested())
        {
            Page::getInstance()->setViewMode(Page::VIEW_MODE_HEADERLESS);

            $header = Page::getInstance()->getHeader();
            $header->addCssFile(Theme::getInstance()->getCssPath(self::package(), true) . 'Print.css', 'print');
        }

        $html = array();

        $html[] = $this->render_header();

        $html[] = '<div class="row">';
        $html[] = $this->getViewRenderer()->render();
        $html[] = '</div>';

        if ($this->isPrintRequested())
        {
            $html[] = '<script type="text/javascript">';
            $html[] = 'window.print();';
            $html[] = '</script>';
        }

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    protected function initialize()
    {
        $header = Page::getInstance()->getHeader();
        $header->addCssFile(
            Theme::getInstance()->getCssPath(\Chamilo\Application\Calendar\Manager::package(), true) . 'Print.css',
            'print');

        $this->set_parameter(ViewRenderer::PARAM_TYPE, $this->getCurrentRendererType());
        $this->set_parameter(ViewRenderer::PARAM_TIME, $this->getCurrentRendererTime());
    }

    /**
     *
     * @return string[]
     */
    protected function getDisplayParameters()
    {
        return array(
            self::PARAM_CONTEXT => self::package(),
            self::PARAM_ACTION => self::ACTION_BROWSE_USER,
            ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
            self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id());
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
                self::PARAM_ACTION => self::ACTION_BROWSE_USER,
                ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
                ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id(),
                self::PARAM_PRINT => 1));

        $generalButtonGroup->addButton(
            new Button(Translation::get('PrinterComponent'), new BootstrapGlyph('print'), $printUrl->getUrl()));

        $iCalUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_USER,
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

        $dropdownButton = new DropdownButton(
            Translation::get('OtherSchedules', null, __NAMESPACE__),
            new FontAwesomeGlyph('calendar-o'));
        $dropdownButton->setDropdownClasses('dropdown-menu-right');

        if ($this->getUser()->get_platformadmin() || $this->getUser()->get_status() == User::STATUS_TEACHER)
        {
            $dropdownButton->addSubButton(new SubButtonHeader(Translation::get('OtherSchedules')));

            $userUrl = new Redirect(
                array(self::PARAM_CONTEXT => self::package(), self::PARAM_ACTION => self::ACTION_USER));

            $dropdownButton->addSubButton(
                new SubButton(
                    Translation::get(self::ACTION_USER . 'Component'),
                    new FontAwesomeGlyph('user'),
                    $userUrl->getUrl()));
        }

        $groupUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_BROWSE_GROUP,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId()));

        $dropdownButton->addSubButton(
            new SubButton(
                Translation::get(self::ACTION_BROWSE_GROUP . 'Component'),
                new FontAwesomeGlyph('users'),
                $groupUrl->getUrl()));

        $locationUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_BROWSE_LOCATION,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId()));

        $dropdownButton->addSubButton(
            new SubButton(
                Translation::get(self::ACTION_BROWSE_LOCATION . 'Component'),
                new FontAwesomeGlyph('map-marker'),
                $locationUrl->getUrl()));

        if ($this->getUser()->get_platformadmin() || $this->getUser()->get_status() == User::STATUS_TEACHER)
        {
            $dropdownButton->addSubButton(new SubButtonDivider());
            $dropdownButton->addSubButton(new SubButtonHeader(Translation::get('Tracking')));

            $progressUrl = new Redirect(
                array(self::PARAM_CONTEXT => self::package(), self::PARAM_ACTION => self::ACTION_PROGRESS));

            $dropdownButton->addSubButton(
                new SubButton(
                    Translation::get(self::ACTION_PROGRESS . 'Component'),
                    new FontAwesomeGlyph('tasks'),
                    $progressUrl->getUrl()));
        }

        $actions[] = $dropdownButton;

        return $actions;
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
