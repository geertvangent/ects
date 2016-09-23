<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Cache\Doctrine\Provider\PhpFileCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Activity;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Group;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\StudentGroup;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CalendarService
{
    const SQL_DATE_FORMAT = 'Y-m-d H:i:s';

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
    public function getEventsForUserAndBetweenDates(User $user, $fromDate = null, $toDate = null)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->getId(), $fromDate, $toDate)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $events = $this->getCalendarRepository()->findEventsForUser($user);

            $cache->save(
                $cacheIdentifier,
                $this->filterEventsBetweenDates($events, $fromDate, $toDate),
                $lifetimeInMinutes * 60);
        }

        return new ArrayResultSet($cache->fetch($cacheIdentifier));
    }

    /**
     *
     * @param string[][] $events
     * @param integer $fromDate
     * @param integer $toDate
     * @return string[][]
     */
    private function filterEventsBetweenDates($events, $fromDate = null, $toDate = null)
    {
        if (is_null($fromDate) && is_null($toDate))
        {
            return $events;
        }
        else
        {
            $filteredEvents = array_filter(
                $events,
                function ($event) use ($fromDate, $toDate)
                {
                    $startTime = strtotime($event[Activity::PROPERTY_START_TIME]);
                    $endTime = strtotime($event[Activity::PROPERTY_END_TIME]);

                    if (is_null($fromDate))
                    {
                        return $endTime <= $toDate;
                    }
                    elseif (is_null($toDate))
                    {
                        return $startTime >= $fromDate;
                    }
                    else
                    {
                        return $startTime >= $fromDate && $endTime <= $toDate;
                    }
                });

            return array_values($filteredEvents);
        }
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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $groupIdentifier, $fromDate, $toDate)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $events = $this->getCalendarRepository()->findEventsForGroup($year, $groupIdentifier);

            $cache->save(
                $cacheIdentifier,
                $this->filterEventsBetweenDates($events, $fromDate, $toDate),
                $lifetimeInMinutes * 60);
        }

        return new ArrayResultSet($cache->fetch($cacheIdentifier));
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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $locationIdentifier, $fromDate, $toDate)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $events = $this->getCalendarRepository()->findEventsForLocation($year, $locationIdentifier);

            $cache->save(
                $cacheIdentifier,
                $this->filterEventsBetweenDates($events, $fromDate, $toDate),
                $lifetimeInMinutes * 60);
        }

        return new ArrayResultSet($cache->fetch($cacheIdentifier));
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
        return new ArrayResultSet(
            $this->getCalendarRepository()->findEventsForUserByModuleIdentifier($user, $moduleIdentifier, $year));
    }

    /**
     *
     * @param string $year
     * @param string $groupIdentifier
     * @param string $identifier
     * @return string[]
     */
    public function getEventForGroupByYearAndIdentifier($year, $groupIdentifier, $eventIdentifier)
    {
        return $this->getCalendarRepository()->findEventForGroupByYearAndIdentifier(
            $year,
            $groupIdentifier,
            $eventIdentifier);
    }

    /**
     *
     * @param string $year
     * @param string $groupIdentifier
     * @param string $moduleIdentifier
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function getEventsForGroupByYearAndModuleIdentifier($year, $groupIdentifier, $moduleIdentifier)
    {
        return new ArrayResultSet(
            $this->getCalendarRepository()->findEventsForGroupByYearAndModuleIdentifier(
                $year,
                $groupIdentifier,
                $moduleIdentifier));
    }

    /**
     *
     * @param string $year
     * @param string $locationIdentifier
     * @param string $identifier
     * @return string[]
     */
    public function getEventForLocationByYearAndIdentifier($year, $locationIdentifier, $eventIdentifier)
    {
        return $this->getCalendarRepository()->findEventForLocationByYearAndIdentifier(
            $year,
            $locationIdentifier,
            $eventIdentifier);
    }

    /**
     *
     * @param string $year
     * @param string $locationIdentifier
     * @param string $moduleIdentifier
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function getEventsForLocationByYearAndModuleIdentifier($year, $locationIdentifier, $moduleIdentifier)
    {
        return new ArrayResultSet(
            $this->getCalendarRepository()->findEventsForLocationByYearAndModuleIdentifier(
                $year,
                $locationIdentifier,
                $moduleIdentifier));
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
        $facultyRecords = $this->getCalendarRepository()->findFacultiesByYearAndUser($year, $user);
        $faculties = array();

        foreach ($facultyRecords as $facultyRecord)
        {
            $faculties[$facultyRecord[StudentGroup::PROPERTY_FACULTY_ID]] = $facultyRecord;
        }

        return $faculties;
    }

    /**
     *
     * @param string $year
     * @param User $user
     * @return string[]
     */
    public function getFacultiesGroupsByYearAndUser($year, User $user)
    {
        $facultiesGroupsRecords = $this->getCalendarRepository()->findFacultiesGroupsByYearAndUser($year, $user);

        $facultiesGroups = array();

        foreach ($facultiesGroupsRecords as $facultiesGroupsRecord)
        {
            $faculties[$facultiesGroupsRecord[StudentGroup::PROPERTY_FACULTY_ID]][$facultiesGroupsRecord[StudentGroup::PROPERTY_GROUP_ID]] = $facultiesGroupsRecord;
        }

        return $facultiesGroups;
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultiesByYear($year)
    {
        $facultyRecords = $this->getCalendarRepository()->findFacultiesByYear($year);
        $faculties = array();

        foreach ($facultyRecords as $facultyRecord)
        {
            $faculties[$facultyRecord[Group::PROPERTY_FACULTY_ID]] = $facultyRecord;
        }

        return $faculties;
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function getFacultyGroupsByYear($year)
    {
        $facultiesGroupsRecords = $this->getCalendarRepository()->findFacultyGroupsByYear($year);

        $facultiesGroups = array();

        foreach ($facultiesGroupsRecords as $facultiesGroupsRecord)
        {
            $facultiesGroups[$facultiesGroupsRecord[Group::PROPERTY_FACULTY_ID]][] = $facultiesGroupsRecord;
        }

        return $facultiesGroups;
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
        $groupsRecords = $this->getCalendarRepository()->findGroupsByYear($year);

        $groups = array();

        foreach ($groupsRecords as $groupsRecord)
        {
            $groups[$groupsRecord[Group::PROPERTY_ID]] = $groupsRecord;
        }

        return $groups;
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
        return $this->getCalendarRepository()->findLocationByYearAndIdentifier($year, $identifier);
    }

    /**
     *
     * @param integer $year
     * @return string[]
     */
    public function getZonesByYear($year)
    {
        return $this->getCalendarRepository()->findZonesByYear($year);
    }

    /**
     *
     * @param integer year
     * @param string $zoneIdentifier
     * @return string[]
     */
    public function getLocationsByYearAndZoneIdentifier($year, $zoneIdentifier)
    {
        return $this->getCalendarRepository()->findLocationsByYearAndZoneIdentifier($year, $zoneIdentifier);
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