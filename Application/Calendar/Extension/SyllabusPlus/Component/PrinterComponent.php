<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Calendar\Renderer\Legend;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRendererFactory;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Configuration\LocalSetting;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider;
use Chamilo\Libraries\Format\Structure\Page;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PrinterComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        Page::getInstance()->setViewMode(Page :: VIEW_MODE_HEADERLESS);

        $this->checkAuthorization();

        $this->set_parameter(ViewRenderer :: PARAM_TYPE, $this->getCurrentRendererType());
        $this->set_parameter(ViewRenderer :: PARAM_TIME, $this->getCurrentRendererTime());

        return $this->render_header() . $this->getCalendarHtml() . $this->render_footer();
    }

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

        $displayParameters = $this->getDisplayParameters();

        $dataProvider = new CalendarRendererProvider(
            new CalendarRendererProviderRepository(),
            $this->getUserCalendar(),
            $this->get_user(),
            $displayParameters);
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
            $renderer->setStartHour(LocalSetting :: getInstance()->get('working_hours_start'));
            $renderer->setEndHour(LocalSetting :: getInstance()->get('working_hours_end'));
            $renderer->setHideOtherHours(LocalSetting :: getInstance()->get('hide_none_working_hours'));
        }

        return $renderer->render();
    }
}
