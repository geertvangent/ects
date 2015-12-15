<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Calendar\Renderer\Form\JumpForm;
use Chamilo\Libraries\Calendar\Renderer\Legend;
use Chamilo\Libraries\Calendar\Renderer\Type\View\MiniMonthRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRendererFactory;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Page;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\LocalSetting;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements DelegateComponent
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

        $header = Page :: getInstance()->getHeader();
        $header->addCssFile(
            Theme :: getInstance()->getCssPath(\Chamilo\Application\Calendar\Manager :: package(), true) . 'Print.css',
            'print');

        $this->form = new JumpForm($this->get_url($this->getDisplayParameters()), $this->getCurrentRendererTime());

        if ($this->form->validate())
        {
            $this->setCurrentRendererTime($this->form->getTime());
        }

        $this->set_parameter(ViewRenderer :: PARAM_TYPE, $this->getCurrentRendererType());
        $this->set_parameter(ViewRenderer :: PARAM_TIME, $this->getCurrentRendererTime());

        $tabs = $this->getTabs();
        $tabs->set_content($this->getCalendarHtml());

        $html = array();

        $html[] = $this->render_header();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return string[]
     */
    private function getDisplayParameters()
    {
        return array(
            self :: PARAM_CONTEXT => self :: package(),
            self :: PARAM_ACTION => self :: ACTION_BROWSER,
            ViewRenderer :: PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer :: PARAM_TIME => $this->getCurrentRendererTime(),
            self :: PARAM_USER_USER_ID => $this->getUserCalendar()->get_id());
    }

    /**
     *
     * @return string
     */
    public function getCalendarHtml()
    {
        BreadcrumbTrail :: get_instance()->add(
            new Breadcrumb(
                null,
                $this->getUserCalendar()->fullname(
                    $this->getUserCalendar()->get_lastname(),
                    $this->getUserCalendar()->get_firstname())));

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
                $this->getCurrentRendererTime());
            $renderer = $rendererFactory->getRenderer();

            if ($this->getCurrentRendererType() == ViewRenderer :: TYPE_DAY ||
                 $this->getCurrentRendererType() == ViewRenderer :: TYPE_WEEK)
            {
                $renderer->setStartHour(
                    LocalSetting :: getInstance()->get('working_hours_start', 'Chamilo\Libraries\Calendar'));
                $renderer->setEndHour(
                    LocalSetting :: getInstance()->get('working_hours_end', 'Chamilo\Libraries\Calendar'));
                $renderer->setHideOtherHours(
                    LocalSetting :: getInstance()->get('hide_non_working_hours', 'Chamilo\Libraries\Calendar'));
            }

            $this->viewRenderer = $renderer;
        }

        return $this->viewRenderer;
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
        $html[] = $this->form->toHtml();

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
            $this->calendarDataProvider = new CalendarRendererProvider(
                $this->getUserCalendar(),
                $this->get_user(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }
}
