<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\GroupCalendarRendererProvider;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Location;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationComponent extends BrowserComponent
{
    const UNORDERED_GROUPS = 'Andere';

    /**
     *
     * @var string
     */
    private $locationIdentifier;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->initialize();
        return $this->renderCalendar();
    }

    /**
     *
     * @return string[]
     */
    protected function getDisplayParameters()
    {
        return array(
            self::PARAM_CONTEXT => self::package(),
            self::PARAM_ACTION => self::ACTION_LOCATION,
            ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
            self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id(),
            self::PARAM_LOCATION_ID => $this->getLocationIdentifier());
    }

    protected function renderYears()
    {
        $calendarService = $this->getCalendarService();

        foreach ($calendarService->getYears() as $year)
        {
            $zones = $calendarService->getZonesByYear($year);

            $html[] = '<h4>' . Translation::get('AcademicYear', array('YEAR' => $year)) . '</h4>';

            $tabs = new DynamicTabsRenderer($year . '-locations');

            foreach ($zones as $zone)
            {
                $content = $this->renderZones($year, $zone);
                $tabs->add_tab(new DynamicContentTab($year . '-' . $zone->getId(), $zone->getCode(), null, $content));
            }

            $html[] = $tabs->render();
        }

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param integer $year
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone $zone
     * @return string
     */
    protected function renderZones($year, Zone $zone)
    {
        $calendarService = $this->getCalendarService();
        $locations = $calendarService->getLocationsByYearAndZoneIdentifier($year, $zone->getId());

        $html = array();

        $html[] = '<ul class="syllabus-group-list">';

        foreach ($locations as $location)
        {
            $html[] = '<li>';
            $html[] = $this->renderLocationLink($location);
            $html[] = '</li>';
        }

        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param string $originalGroupName
     * @return string
     */
    protected function renderLocationLink(Location $location)
    {
        $locationLink = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_LOCATION,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                self::PARAM_YEAR => $location->getYear(),
                self::PARAM_LOCATION_ID => $location->getId()));

        $name = $location->getCode();

        if (! empty($location->getName()) && $location->getName() != $location->getCode())
        {
            $name .= ' <small class="text-muted">(' . $location->getName() . ')</small>';
        }

        return '<a href="' . $locationLink->getUrl() . '">' . $name . '</a>';
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    protected function getCalendarService()
    {
        if (! isset($this->calendarService))
        {
            $this->calendarService = new CalendarService(new CalendarRepository());
        }

        return $this->calendarService;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider
     */
    protected function getCalendarDataProvider()
    {
        if (! isset($this->calendarDataProvider))
        {
            $this->calendarDataProvider = new GroupCalendarRendererProvider(
                $this->getLocationIdentifier(),
                $this->getUserCalendar(),
                $this->get_user(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }

    protected function renderCalendar()
    {
        $groupUrl = new Redirect($this->getDisplayParameters(), array(self::PARAM_LOCATION_ID));

        BreadcrumbTrail::get_instance()->add(new Breadcrumb($groupUrl->getUrl(), Translation::get('LocationComponent')));

        $locationIdentifier = $this->getLocationIdentifier();

        if ($locationIdentifier)
        {
            $group = $this->getCalendarService()->getGroupByIdentifier($locationIdentifier);

            BreadcrumbTrail::get_instance()->add(new Breadcrumb(null, $group['name']));

            return parent::renderCalendar();
        }
        else
        {

            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->renderYears();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }

    /**
     *
     * @return string
     */
    protected function getLocationIdentifier()
    {
        if (! isset($this->locationIdentifier))
        {
            $this->locationIdentifier = $this->getRequest()->query->get(self::PARAM_GROUP_ID);
        }

        return $this->locationIdentifier;
    }
}
