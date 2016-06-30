<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Location;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CalendarService
{

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository
     */
    private $calendarRepository;

    /**
     *
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository $calendarRepository
     */
    public function __construct(CalendarRepository $calendarRepository)
    {
        $this->CalendarRepository = $calendarRepository;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository
     */
    public function getCalendarRepository()
    {
        return $this->CalendarRepository;
    }

    /**
     *
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository $calendarRepository
     */
    public function setCalendarRepository(CalendarRepository $calendarRepository)
    {
        $this->CalendarRepository = $calendarRepository;
    }

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @param integer $fromDate
     * @param integer $toDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function getEventsForUserAndBetweenDates(User $user, $fromDate, $toDate)
    {
        return $this->getCalendarRepository()->findEventsForUserAndBetweenDates($user, $fromDate, $toDate);
    }

    /**
     *
     * @param string $groupIdentifier
     * @param integer $fromDate
     * @param integer $toDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function getEventsForGroupAndBetweenDates($year, $groupIdentifier, $fromDate, $toDate)
    {
        return $this->getCalendarRepository()->findEventsForGroupAndBetweenDates(
            $year,
            $groupIdentifier,
            $fromDate,
            $toDate);
    }

    /**
     *
     * @param string $year
     * @param string $locationIdentifier
     * @param integer $fromDate
     * @param integer $toDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function getEventsByYearAndLocationAndBetweenDates($year, $locationIdentifier, $fromDate, $toDate)
    {
        return $this->getCalendarRepository()->findEventsByYearAndLocationAndBetweenDates(
            $year,
            $locationIdentifier,
            $fromDate,
            $toDate);
    }

    /**
     *
     * @param User $user
     * @param string $identifier
     * @return string[]
     */
    public function getEventForUserByIdentifier(User $user, $identifier, $year)
    {
        return $this->getCalendarRepository()->findEventForUserByIdentifier($user, $identifier, $year);
    }

    /**
     *
     * @param User $user
     * @param string $moduleIdentifier
     * @return string[]
     */
    public function getEventsForUserByModuleIdentifier(User $user, $moduleIdentifier, $year)
    {
        return $this->getCalendarRepository()->findEventsForUserByModuleIdentifier($user, $moduleIdentifier, $year);
    }

    /**
     *
     * @param \Chamilo\Configuration\Configuration $configuration
     * @return boolean
     */
    public function isConfigured(\Chamilo\Configuration\Configuration $configuration)
    {
        $namespace = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::package();

        $hasDriver = $configuration->get_setting(array($namespace, 'dbms'));
        $hasUser = $configuration->get_setting(array($namespace, 'user'));
        $hasPassword = $configuration->get_setting(array($namespace, 'password'));
        $hasHost = $configuration->get_setting(array($namespace, 'host'));
        $hasDatabase = $configuration->get_setting(array($namespace, 'database'));

        return $hasDriver && $hasUser && $hasPassword && $hasHost && $hasDatabase;
    }

    /**
     *
     * @param string $year
     * @param User $user
     * @return string[]
     */
    public function getFacultiesByYearAndUser($year, User $user)
    {
        return $this->getCalendarRepository()->findFacultiesByYearAndUser($year, $user);
    }

    /**
     *
     * @param string $year
     * @param User $user
     * @return string[]
     */
    public function getFacultiesGroupsByYearAndUser($year, User $user)
    {
        return $this->getCalendarRepository()->findFacultiesGroupsByYearAndUser($year, $user);
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultiesByYear($year)
    {
        return $this->getCalendarRepository()->findFacultiesByYear($year);
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultyGroupsByYear($year)
    {
        return $this->getCalendarRepository()->findFacultyGroupsByYear($year);
    }

    /**
     *
     * @param string $year
     * @param string $facultyCode
     * @return string[]
     */
    public function getFacultyGroupsByYearAndCode($year, $facultyCode)
    {
        $groups = $this->getFacultyGroupsByYear($year);
        return $groups[$facultyCode];
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getGroupsByYear($year)
    {
        return $this->getCalendarRepository()->findGroupsByYear($year);
    }

    /**
     *
     * @param string $year
     * @param string $identifier
     * @return string[]
     */
    public function getGroupByYearAndIdentifier($year, $identifier)
    {
        $groups = $this->getGroupsByYear($year);
        return $groups[$identifier];
    }

    /**
     *
     * @param string $year
     * @param string $identifier
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Location
     */
    public function getLocationByYearAndIdentifier($year, $identifier)
    {
        $locationRecord = $this->getCalendarRepository()->findLocationByYearAndIdentifier($year, $identifier);

        return new Location(
            $locationRecord['year'],
            $locationRecord['location_id'],
            $locationRecord['location_code'],
            $locationRecord['location_name']);
    }

    /**
     *
     * @param integer $year
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Zone[]
     */
    public function getZonesByYear($year)
    {
        $zoneRecords = $this->getCalendarRepository()->findZonesByYear($year);
        $zones = array();

        foreach ($zoneRecords as $zoneRecord)
        {
            $zones[] = new Zone(
                $zoneRecord['year'],
                $zoneRecord['zone_id'],
                $zoneRecord['zone_code'],
                $zoneRecord['zone_name']);
        }

        return $zones;
    }

    /**
     *
     * @param integer year
     * @param string $zoneIdentifier
     * @return string[]
     */
    public function getLocationsByYearAndZoneIdentifier($year, $zoneIdentifier)
    {
        $locationRecords = $this->getCalendarRepository()->findLocationsByYearAndZoneIdentifier($year, $zoneIdentifier);
        $locations = array();

        foreach ($locationRecords as $locationRecord)
        {
            $locations[] = new Location(
                $locationRecord['year'],
                $locationRecord['location_id'],
                $locationRecord['location_code'],
                $locationRecord['location_name']);
        }

        return $locations;
    }

    /**
     *
     * @return string[]
     */
    public function getYears()
    {
        return explode(
            ',',
            Configuration::get_instance()->get_setting(
                array('Ehb\Application\Calendar\Extension\SyllabusPlus', 'years')));
    }
}