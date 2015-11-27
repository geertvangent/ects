<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Calendar\Renderer\Form\JumpForm;
use Chamilo\Libraries\Calendar\Renderer\Legend;
use Chamilo\Libraries\Calendar\Renderer\Type\View\MiniMonthRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRendererFactory;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Configuration\LocalSetting;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @var JumpForm
     */
    private $form;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->checkAuthorization();

        $html = array();

        $this->set_parameter(ViewRenderer :: PARAM_TYPE, $this->getCurrentRendererType());
        $this->set_parameter(ViewRenderer :: PARAM_TIME, $this->getCurrentRendererTime());

        $this->form = new JumpForm($this->get_url(), $this->getCurrentRendererTime());

        if ($this->form->validate())
        {
            $this->currentTime = $this->form->getTime();
        }
        $tabs = $this->getTabs();
        $tabs->set_content($this->getCalendarHtml());

        $html[] = $this->render_header();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
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

        $displayParameters = array(
            self :: PARAM_CONTEXT => self :: package(),
            self :: PARAM_ACTION => self :: ACTION_VIEW,
            ViewRenderer :: PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer :: PARAM_TIME => $this->getCurrentRendererTime(),
            self :: PARAM_USER_USER_ID => $this->getUserCalendar()->get_id());

        $dataProvider = new CalendarRendererProvider(
            new CalendarRendererProviderRepository(),
            $this->getUserCalendar(),
            $this->get_user(),
            $displayParameters);
        $calendarLegend = new Legend($dataProvider);

        $mini_month_renderer = new MiniMonthRenderer(
            $dataProvider,
            $calendarLegend,
            $this->getCurrentRendererTime(),
            null,
            $this->getMiniMonthMarkPeriod());

        $rendererFactory = new ViewRendererFactory(
            $this->getCurrentRendererType(),
            $dataProvider,
            $calendarLegend,
            $this->getCurrentRendererTime());
        $renderer = $rendererFactory->getRenderer();

        if ($this->getCurrentRendererType() == ViewRenderer :: TYPE_DAY ||
             $this->getCurrentRendererType() == ViewRenderer :: TYPE_WEEK)
        {
            $renderer->setStartHour(LocalSetting :: getInstance()->get('working_hours_start'));
            $renderer->setEndHour(LocalSetting :: getInstance()->get('working_hours_end'));
            $renderer->setHideOtherHours(LocalSetting :: getInstance()->get('hide_none_working_hours'));
        }

        $html = array();

        $html[] = '<div class="mini_calendar">';
        $html[] = $mini_month_renderer->render();
        $html[] = $this->form->toHtml();
        $html[] = '</div>';
        $html[] = '<div class="normal_calendar">';
        $html[] = $renderer->render();
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }
}
