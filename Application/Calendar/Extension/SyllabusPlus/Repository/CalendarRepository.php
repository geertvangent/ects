<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Repository;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Cache\Doctrine\Provider\FilesystemCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\DataClass\Property\DataClassProperties;
use Chamilo\Libraries\Storage\DataManager\Repository\DataClassRepository;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrieveParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\GroupBy;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\FunctionConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertiesConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Group;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Location;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\ScheduledGroup;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\StudentGroup;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Repository
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CalendarRepository
{

    /**
     *
     * @var \Chamilo\Libraries\Storage\DataManager\Repository\DataClassRepository
     */
    private $dataClassRepository;

    /**
     *
     * @param \Chamilo\Libraries\Storage\DataManager\Repository\DataClassRepository $dataClassRepository
     */
    public function __construct(DataClassRepository $dataClassRepository)
    {
        $this->dataClassRepository = $dataClassRepository;
    }

    /**
     *
     * @return \Chamilo\Libraries\Storage\DataManager\Repository\DataClassRepository
     */
    protected function getDataClassRepository()
    {
        return $this->dataClassRepository;
    }

    /**
     *
     * @param \Chamilo\Libraries\Storage\DataManager\Repository\DataClassRepository $dataClassRepository
     */
    protected function setDataClassRepository($dataClassRepository)
    {
        $this->dataClassRepository = $dataClassRepository;
    }

    /**
     *
     * @param string $dataClass
     * @param string[][] $records
     * @param unknown $fieldsToAggregate
     * @return string[][]
     */
    private function aggregateRecords($dataClass, $records, $fieldsToAggregate)
    {
        $dataClassProperties = $dataClass::get_default_property_names();
        $importantValueKeys = array_diff($dataClassProperties, $fieldsToAggregate);

        $aggregatedRecords = array();

        foreach ($records as $record)
        {
            $importantValues = $this->filterProperties($record, $importantValueKeys);
            $hash = md5(json_encode($importantValues));

            if (! isset($aggregatedRecords[$hash]))
            {
                $aggregatedRecords[$hash] = $importantValues;

                foreach ($fieldsToAggregate as $fieldToAggregate)
                {
                    $aggregatedRecords[$hash][$fieldToAggregate] = array();
                }
            }

            foreach ($fieldsToAggregate as $fieldToAggregate)
            {
                if (isset($record[$fieldToAggregate]) &&
                     ! in_array($record[$fieldToAggregate], $aggregatedRecords[$hash][$fieldToAggregate]))
                {
                    $aggregatedRecords[$hash][$fieldToAggregate][] = $record[$fieldToAggregate];
                }
            }
        }

        foreach ($aggregatedRecords as &$aggregatedRecord)
        {
            foreach ($fieldsToAggregate as $fieldToAggregate)
            {
                sort($aggregatedRecord[$fieldToAggregate]);
                $aggregatedRecord[$fieldToAggregate] = implode(', ', $aggregatedRecord[$fieldToAggregate]);
            }
        }

        return array_values($aggregatedRecords);
    }

    /**
     *
     * @param string[] $properties
     * @param string[] $propertyKeysToMaintain
     * @return string[]
     */
    private function filterProperties($properties, $propertyKeysToMaintain)
    {
        return array_filter(
            $properties,
            function ($key) use ($propertyKeysToMaintain)
            {
                return in_array($key, $propertyKeysToMaintain);
            },
            ARRAY_FILTER_USE_KEY);
    }

    /**
     *
     * @param string $className
     * @param string $year
     * @return string
     */
    private function getYearSpecificClassName($className, $year)
    {
        return '\Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Year' . $this->convertYear($year) .
             '\\' . $className;
    }

    /**
     *
     * @param string $className
     * @param string[][] $activities
     * @return string[][]
     */
    private function aggregateActivities($className, $activities)
    {
        return $this->aggregateRecords(
            $className,
            $activities,
            array($className::PROPERTY_LOCATION, $className::PROPERTY_STUDENT_GROUP, $className::PROPERTY_TEACHER));
    }

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @return string[][]
     */
    public function findEventsForUser(User $user)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->getId())));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $records = array();

            if ($user->get_official_code())
            {
                $baseClass = $this->getYearSpecificClassName(
                    $user->is_teacher() ? 'TeacherActivity' : 'StudentActivity',
                    '2016-17');

                $condition = new EqualityCondition(
                    new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_PERSON_ID),
                    new StaticConditionVariable((string) $user->get_official_code()));

                $activities = $this->getDataClassRepository()->records(
                    $baseClass,
                    new RecordRetrievesParameters(
                        null,
                        $condition,
                        null,
                        null,
                        array(new OrderBy(new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_START_TIME)))));

                $records = $this->aggregateActivities($baseClass, $activities);
            }

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $groupIdentifier
     * @return string[][]
     */
    public function findEventsForGroup($year, $groupIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $groupIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $className = $this->getYearSpecificClassName('GroupActivity', $year);

            $condition = new EqualityCondition(
                new PropertyConditionVariable($className, $className::PROPERTY_GROUP_ID),
                new StaticConditionVariable((string) $groupIdentifier));

            $activities = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    $condition,
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($className, $className::PROPERTY_START_TIME)))));

            $records = $this->aggregateActivities($className, $activities);

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $records;
    }

    /**
     *
     * @param string $groupIdentifier
     * @return string[][]
     */
    public function findEventsForLocation($year, $locationIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $locationIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $className = $this->getYearSpecificClassName('LocationActivity', $year);

            $condition = new EqualityCondition(
                new PropertyConditionVariable($className, $className::PROPERTY_LOCATION_ID),
                new StaticConditionVariable((string) $locationIdentifier));

            $activities = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    $condition,
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($className, $className::PROPERTY_START_TIME)))));

            $records = $this->aggregateActivities($className, $activities);

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $records;
    }

    /**
     *
     * @return string[]
     */
    public function getYears()
    {
        return explode(
            ',',
            Configuration::getInstance()->get_setting(
                array('Ehb\Application\Calendar\Extension\SyllabusPlus', 'years')));
    }

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @param string $moduleIdentifier
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findEventsForUserByModuleIdentifier(User $user, $moduleIdentifier, $year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->get_id(), $moduleIdentifier, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $records = array();

            if ($user->get_official_code())
            {
                $baseClass = $this->getYearSpecificClassName(
                    $user->is_teacher() ? 'TeacherActivity' : 'StudentActivity',
                    $year);

                $conditions = array();

                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_PERSON_ID),
                    new StaticConditionVariable((string) $user->get_official_code()));

                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_MODULE_ID),
                    new StaticConditionVariable((string) $moduleIdentifier));

                $activities = $this->getDataClassRepository()->records(
                    $baseClass,
                    new RecordRetrievesParameters(
                        null,
                        new AndCondition($conditions),
                        null,
                        null,
                        array(new OrderBy(new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_START_TIME)))));

                $records = $this->aggregateActivities($baseClass, $activities);
            }

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $records;
    }

    /**
     *
     * @param User $user
     * @param string $identifier
     * @return string[]
     */
    public function findEventForUserByIdentifier(User $user, $identifier, $year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->get_id(), $identifier, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $record = array();

            if ($user->get_official_code())
            {
                $baseClass = $this->getYearSpecificClassName(
                    $user->is_teacher() ? 'TeacherActivity' : 'StudentActivity',
                    $year);

                $conditions = array();

                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_PERSON_ID),
                    new StaticConditionVariable((string) $user->get_official_code()));

                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_ID),
                    new StaticConditionVariable((string) $identifier));

                $activities = $this->getDataClassRepository()->records(
                    $baseClass,
                    new RecordRetrievesParameters(
                        null,
                        new AndCondition($conditions),
                        null,
                        null,
                        array(new OrderBy(new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_START_TIME)))));

                $records = $this->aggregateActivities($baseClass, $activities);

                if (count($records) > 0)
                {
                    $record = array_shift($records);
                }
            }

            $cache->save($cacheIdentifier, $record, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $record;
    }

    /**
     *
     * @param string $year
     * @param string $groupIdentifier
     * @param string $identifier
     * @return string[]
     */
    public function findEventForGroupByYearAndIdentifier($year, $groupIdentifier, $eventIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $groupIdentifier, $eventIdentifier, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $record = array();

            $baseClass = $this->getYearSpecificClassName('GroupActivity', $year);

            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_GROUP_ID),
                new StaticConditionVariable((string) $groupIdentifier));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_ID),
                new StaticConditionVariable((string) $eventIdentifier));

            $activities = $this->getDataClassRepository()->records(
                $baseClass,
                new RecordRetrievesParameters(
                    null,
                    new AndCondition($conditions),
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_START_TIME)))));

            $records = $this->aggregateActivities($baseClass, $activities);

            if (count($records) > 0)
            {
                $record = array_shift($records);
            }

            $cache->save($cacheIdentifier, $record, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $record;
    }

    /**
     *
     * @param string $year
     * @param string $groupIdentifier
     * @param string $moduleIdentifier
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function findEventsForGroupByYearAndModuleIdentifier($year, $groupIdentifier, $moduleIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $groupIdentifier, $moduleIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $className = $this->getYearSpecificClassName('GroupActivity', $year);

            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($className, $className::PROPERTY_GROUP_ID),
                new StaticConditionVariable((string) $groupIdentifier));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($className, $className::PROPERTY_MODULE_ID),
                new StaticConditionVariable((string) $moduleIdentifier));

            $activities = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    new AndCondition($conditions),
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($className, $className::PROPERTY_START_TIME)))));

            $records = $this->aggregateActivities($className, $activities);

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $records;
    }

    /**
     *
     * @param string $year
     * @param string $locationIdentifier
     * @param string $identifier
     * @return string[]
     */
    public function findEventForLocationByYearAndIdentifier($year, $locationIdentifier, $eventIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $locationIdentifier, $eventIdentifier, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $record = array();

            $baseClass = $this->getYearSpecificClassName('LocationActivity', $year);

            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_LOCATION_ID),
                new StaticConditionVariable((string) $locationIdentifier));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_ID),
                new StaticConditionVariable((string) $eventIdentifier));

            $activities = $this->getDataClassRepository()->records(
                $baseClass,
                new RecordRetrievesParameters(
                    null,
                    new AndCondition($conditions),
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_START_TIME)))));

            $records = $this->aggregateActivities($baseClass, $activities);

            if (count($records) > 0)
            {
                $record = array_shift($records);
            }

            $cache->save($cacheIdentifier, $record, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $record;
    }

    /**
     *
     * @param string $year
     * @param string $locationIdentifier
     * @param string $moduleIdentifier
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function findEventsForLocationByYearAndModuleIdentifier($year, $locationIdentifier, $moduleIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $locationIdentifier, $moduleIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::getInstance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));
            $className = $this->getYearSpecificClassName('LocationActivity', $year);

            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($className, $className::PROPERTY_LOCATION_ID),
                new StaticConditionVariable((string) $locationIdentifier));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable($className, $className::PROPERTY_MODULE_ID),
                new StaticConditionVariable((string) $moduleIdentifier));

            $activities = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    new AndCondition($conditions),
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($className, $className::PROPERTY_START_TIME)))));

            $records = $this->aggregateActivities($className, $activities);

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);

        // return $records;
    }

    /**
     *
     * @param string $year
     * @param User $user
     * @return string[]
     */
    public function findFacultiesByYearAndUser($year, User $user)
    {
        if ($user->get_official_code())
        {
            $className = $this->getYearSpecificClassName('StudentGroup', $year);

            $condition = new EqualityCondition(
                new PropertyConditionVariable($className, StudentGroup::PROPERTY_PERSON_ID),
                new StaticConditionVariable($user->get_official_code()));

            return $this->getDataClassRepository()->distinct(
                $className,
                new DataClassDistinctParameters(
                    $condition,
                    array(
                        StudentGroup::PROPERTY_FACULTY_ID,
                        StudentGroup::PROPERTY_FACULTY_CODE,
                        StudentGroup::PROPERTY_FACULTY_NAME)));
        }
        else
        {
            return array();
        }
    }

    /**
     *
     * @param User $user
     * @return string[]
     */
    public function findFacultiesGroupsByYearAndUser($year, User $user)
    {
        if ($user->get_official_code())
        {
            $className = $this->getYearSpecificClassName('StudentGroup', $year);

            $condition = new EqualityCondition(
                new PropertyConditionVariable($className, StudentGroup::PROPERTY_PERSON_ID),
                new StaticConditionVariable($user->get_official_code()));

            return $this->getDataClassRepository()->distinct(
                $className,
                new DataClassDistinctParameters(
                    $condition,
                    array(
                        StudentGroup::PROPERTY_GROUP_ID,
                        StudentGroup::PROPERTY_GROUP_CODE,
                        StudentGroup::PROPERTY_GROUP_NAME,
                        StudentGroup::PROPERTY_FACULTY_ID,
                        StudentGroup::PROPERTY_FACULTY_CODE,
                        StudentGroup::PROPERTY_FACULTY_NAME)));
        }
        else
        {
            return array();
        }
    }

    /**
     *
     * @param $year
     * @return string[]
     */
    public function findFacultiesByYear($year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $className = $this->getYearSpecificClassName('Group', $year);

            $faculties = $this->getDataClassRepository()->distinct(
                $className,
                new DataClassDistinctParameters(
                    null,
                    array(Group::PROPERTY_FACULTY_ID, Group::PROPERTY_FACULTY_CODE, Group::PROPERTY_FACULTY_NAME),
                    null,
                    array(new OrderBy(new PropertyConditionVariable($className, Group::PROPERTY_FACULTY_NAME)))));

            $cache->save($cacheIdentifier, $faculties);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param $year
     * @return string[]
     */
    public function findFacultyGroupsByYear($year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $className = $this->getYearSpecificClassName('Group', $year);

            $facultiesGroups = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    null,
                    null,
                    null,
                    array(
                        new OrderBy(new PropertyConditionVariable($className, Group::PROPERTY_FACULTY_ID)),
                        new OrderBy(new PropertyConditionVariable($className, Group::PROPERTY_NAME)))));

            $cache->save($cacheIdentifier, $facultiesGroups);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $year
     * @return string[]
     */
    public function findGroupsByYear($year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $className = $this->getYearSpecificClassName('Group', $year);

            $groups = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    null,
                    null,
                    null,
                    array(new OrderBy(new PropertyConditionVariable($className, Group::PROPERTY_NAME)))));

            $cache->save($cacheIdentifier, $groups);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param integer $year
     * @return string[]
     */
    public function findZonesByYear($year)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $className = $this->getYearSpecificClassName('Location', $year);

            $zones = $this->getDataClassRepository()->distinct(
                $className,
                new DataClassDistinctParameters(
                    null,
                    array(
                        Location::PROPERTY_YEAR,
                        Location::PROPERTY_ZONE_ID,
                        Location::PROPERTY_ZONE_CODE,
                        Location::PROPERTY_ZONE_NAME),
                    null,
                    array(
                        new OrderBy(new PropertyConditionVariable($className, Location::PROPERTY_YEAR)),
                        new OrderBy(new PropertyConditionVariable($className, Location::PROPERTY_ZONE_CODE)),
                        new OrderBy(new PropertyConditionVariable($className, Location::PROPERTY_ZONE_NAME)))));

            $cache->save($cacheIdentifier, $zones);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param integer $year
     * @param string $zoneIdentifier
     * @return string[]
     */
    public function findLocationsByYearAndZoneIdentifier($year, $zoneIdentifier)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $zoneIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $className = $this->getYearSpecificClassName('Location', $year);

            $condition = new EqualityCondition(
                new PropertyConditionVariable($className, Location::PROPERTY_ZONE_ID),
                new StaticConditionVariable($zoneIdentifier));

            $locations = $this->getDataClassRepository()->records(
                $className,
                new RecordRetrievesParameters(
                    null,
                    $condition,
                    null,
                    null,
                    array(
                        new OrderBy(new PropertyConditionVariable($className, Location::PROPERTY_CODE)),
                        new OrderBy(new PropertyConditionVariable($className, Location::PROPERTY_NAME)))));

            $cache->save($cacheIdentifier, $locations);
        }

        return $cache->fetch($cacheIdentifier);
    }

    private function convertYear($year)
    {
        $yearParts = explode('-', $year);
        return substr($yearParts[0], 2, 2) . $yearParts[1];
    }

    /**
     *
     * @param string $year
     * @param string $identifier
     */
    public function findLocationByYearAndIdentifier($year, $identifier)
    {
        $className = $this->getYearSpecificClassName('Location', $year);

        $condition = new EqualityCondition(
            new PropertyConditionVariable($className, Location::PROPERTY_ID),
            new StaticConditionVariable($identifier));

        return $this->getDataClassRepository()->record(
            $className,
            new RecordRetrieveParameters(
                new DataClassProperties(new PropertiesConditionVariable($className::class_name())),
                $condition));
    }

    /**
     *
     * @param string $year
     * @return string[][]
     */
    public function findScheduledGroupsByYear($year)
    {
        $properties = array();

        $properties[] = new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_FACULTY_ID);
        $properties[] = new PropertyConditionVariable(
            ScheduledGroup::class_name(),
            ScheduledGroup::PROPERTY_FACULTY_NAME);
        $properties[] = new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_TRAINING_ID);
        $properties[] = new PropertyConditionVariable(
            ScheduledGroup::class_name(),
            ScheduledGroup::PROPERTY_TRAINING_NAME);

        $properties[] = new FunctionConditionVariable(
            FunctionConditionVariable::SUM,
            new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_SCHEDULED),
            ScheduledGroup::PROPERTY_COUNT_SCHEDULED);

        $properties[] = new FunctionConditionVariable(
            FunctionConditionVariable::COUNT,
            new StaticConditionVariable(1),
            ScheduledGroup::PROPERTY_COUNT_TO_BE_SCHEDULED);

        $groupBy = new GroupBy(
            array(
                new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_FACULTY_ID),
                new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_FACULTY_NAME),
                new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_TRAINING_ID),
                new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_TRAINING_NAME)));

        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_YEAR),
            new StaticConditionVariable($year));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(ScheduledGroup::class_name(), ScheduledGroup::PROPERTY_STRUCK),
            new StaticConditionVariable(0));

        return $this->getDataClassRepository()->records(
            ScheduledGroup::class_name(),
            new RecordRetrievesParameters(
                new DataClassProperties($properties),
                new AndCondition($conditions),
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(
                            ScheduledGroup::class_name(),
                            ScheduledGroup::PROPERTY_FACULTY_NAME)),
                    new OrderBy(
                        new PropertyConditionVariable(
                            ScheduledGroup::class_name(),
                            ScheduledGroup::PROPERTY_TRAINING_NAME))),
                null,
                $groupBy));
    }
}