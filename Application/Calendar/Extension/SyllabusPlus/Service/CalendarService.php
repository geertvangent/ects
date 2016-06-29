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
    public function getEventsForGroupAndBetweenDates($groupIdentifier, $fromDate, $toDate)
    {
        return $this->getCalendarRepository()->findEventsForGroupAndBetweenDates($groupIdentifier, $fromDate, $toDate);
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
     * @param User $user
     * @return string[]
     */
    public function getFacultiesForUser(User $user)
    {
        return $this->getCalendarRepository()->findFacultiesForUser($user);
    }

    /**
     *
     * @param User $user
     * @return string[]
     */
    public function getFacultiesGroupsForUser(User $user)
    {
        return $this->getCalendarRepository()->findFacultiesGroupsForUser($user);
    }

    /**
     *
     * @return string[]
     */
    public function getFaculties()
    {
        return $this->getCalendarRepository()->findFaculties();
    }

    /**
     *
     * @return string[]
     */
    public function getFacultyGroups()
    {
        return $this->getCalendarRepository()->findFacultyGroups();
    }

    /**
     *
     * @param string $facultyCode
     * @return string[]
     */
    public function getFacultyGroupsByCode($facultyCode)
    {
        $groups = $this->getFacultyGroups();
        return $groups[$facultyCode];
    }

    /**
     *
     * @return string[]
     */
    public function getGroups()
    {
        return $this->getCalendarRepository()->findGroups();
    }

    /**
     *
     * @return string[]
     */
    public function getGroupByIdentifier($identifier)
    {
        $groups = $this->getGroups();
        return $groups[$identifier];
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