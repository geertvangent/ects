<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Glyph\BootstrapGlyph;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\LocationCalendarRendererProvider;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Location;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationBrowserComponent extends UserBrowserComponent
{

    /**
     *
     * @var string
     */
    private $locationIdentifier;

    /**
     *
     * @var string
     */
    private $year;

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
            self::PARAM_ACTION => self::ACTION_BROWSE_LOCATION,
            ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
            ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
            self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id(),
            self::PARAM_LOCATION_ID => $this->getLocationIdentifier(),
            self::PARAM_YEAR => $this->getYear());
    }

    /**
     *
     * @return string
     */
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
                $tabs->add_tab(
                    new DynamicContentTab(
                        $year . '-' . $zone[Location::PROPERTY_ZONE_ID],
                        $zone[Location::PROPERTY_ZONE_CODE],
                        null,
                        $content));
            }

            $html[] = $tabs->render();
        }

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param integer $year
     * @param string[] $zone
     * @return string
     */
    protected function renderZones($year, $zone)
    {
        $calendarService = $this->getCalendarService();
        $locations = $calendarService->getLocationsByYearAndZoneIdentifier($year, $zone[Location::PROPERTY_ZONE_ID]);

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
    protected function renderLocationLink($location)
    {
        $locationLink = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => self::ACTION_BROWSE_LOCATION,
                self::PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                self::PARAM_YEAR => $location[Location::PROPERTY_YEAR],
                self::PARAM_LOCATION_ID => $location[Location::PROPERTY_ID]));

        $name = $location[Location::PROPERTY_CODE];

        if (! empty($location[Location::PROPERTY_NAME]) &&
             $location[Location::PROPERTY_NAME] != $location[Location::PROPERTY_CODE])
        {
            $name .= ' <small class="text-muted">(' . $location[Location::PROPERTY_NAME] . ')</small>';
        }

        return '<a href="' . $locationLink->getUrl() . '">' . $name . '</a>';
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider
     */
    protected function getCalendarDataProvider()
    {
        if (! isset($this->calendarDataProvider))
        {
            $this->calendarDataProvider = new LocationCalendarRendererProvider(
                $this->getService('ehb.application.calendar.extension.syllabus_plus.service.calendar_service'),
                $this->getYear(),
                $this->getLocationIdentifier(),
                $this->getUserCalendar(),
                $this->get_user(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }

    protected function renderCalendar()
    {
        $locationUrl = new Redirect($this->getDisplayParameters(), array(self::PARAM_LOCATION_ID));

        BreadcrumbTrail::getInstance()->add(
            new Breadcrumb($locationUrl->getUrl(), Translation::get('LocationBrowserComponent')));

        $locationIdentifier = $this->getLocationIdentifier();
        $year = $this->getYear();

        if ($locationIdentifier && $year)
        {
            BreadcrumbTrail::getInstance()->add(
                new Breadcrumb(null, Translation::get('AcademicYear', array('YEAR' => $year))));

            $location = $this->getCalendarService()->getLocationByYearAndIdentifier($year, $locationIdentifier);

            BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $location[Location::PROPERTY_CODE]));
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
            $this->locationIdentifier = $this->getRequest()->query->get(self::PARAM_LOCATION_ID);
        }

        return $this->locationIdentifier;
    }

    /**
     *
     * @return string
     */
    protected function getYear()
    {
        if (! isset($this->year))
        {
            $this->year = $this->getRequest()->query->get(self::PARAM_YEAR);
        }

        return $this->year;
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\UserBrowserComponent::getViewActions()
     */
    protected function getViewActions()
    {
        $actions = array();

        $generalButtonGroup = new ButtonGroup();

        $printUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_BROWSE_LOCATION,
                ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
                ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_LOCATION_ID => $this->getLocationIdentifier(),
                self::PARAM_PRINT => 1));

        $generalButtonGroup->addButton(
            new Button(Translation::get('PrinterComponent'), new BootstrapGlyph('print'), $printUrl->getUrl()));

        $iCalUrl = new Redirect(
            array(
                self::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_LOCATION,
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_LOCATION_ID => $this->getLocationIdentifier()));

        $generalButtonGroup->addButton(
            new Button(Translation::get('ICalExternal'), new BootstrapGlyph('globe'), $iCalUrl->getUrl()));

        $actions[] = $generalButtonGroup;

        return $actions;
    }
}
