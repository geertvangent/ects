<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Repository;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Cache\Doctrine\Provider\FilesystemCache;
use Chamilo\Libraries\Cache\Doctrine\Provider\PhpFileCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\StudentActivity;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\TeacherActivity;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\UserActivity;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataManager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\GroupActivity;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\LocationActivity;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Repository
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CalendarRepository
{
    const SQL_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository
     */
    private static $instance;

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository
     */
    static public function getInstance()
    {
        if (is_null(static::$instance))
        {
            self::$instance = new static();
        }

        return static::$instance;
    }

    /**
     *
     * @param string $dataClass
     * @param string[][] $records
     * @param unknown $fieldsToAggregate
     * @return string
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
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @return string[][]
     */
    public function findEventsForUser(User $user)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->getId())));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            if ($user->get_official_code())
            {
                $baseClass = $user->is_teacher() ? TeacherActivity::class_name() : StudentActivity::class_name();

                $condition = new EqualityCondition(
                    new PropertyConditionVariable($baseClass, UserActivity::PROPERTY_PERSON_ID),
                    new StaticConditionVariable((string) $user->get_official_code()));

                $activities = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::records(
                    $baseClass,
                    new RecordRetrievesParameters(
                        null,
                        $condition,
                        null,
                        null,
                        array(new OrderBy(new PropertyConditionVariable($baseClass, UserActivity::PROPERTY_START_TIME)))))->as_array();

                $records = $this->aggregateRecords(
                    $baseClass,
                    $activities,
                    array(
                        UserActivity::PROPERTY_LOCATION,
                        UserActivity::PROPERTY_STUDENT_GROUP,
                        UserActivity::PROPERTY_TEACHER));
            }
            else
            {
                $records = array();
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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $groupIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $condition = new EqualityCondition(
                new PropertyConditionVariable(GroupActivity::class_name(), GroupActivity::PROPERTY_GROUP_ID),
                new StaticConditionVariable((string) $groupIdentifier));

            $activities = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::records(
                GroupActivity::class_name(),
                new RecordRetrievesParameters(
                    null,
                    $condition,
                    null,
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(
                                GroupActivity::class_name(),
                                GroupActivity::PROPERTY_START_TIME)))))->as_array();

            $records = $this->aggregateRecords(
                GroupActivity::class_name(),
                $activities,
                array(
                    GroupActivity::PROPERTY_LOCATION,
                    GroupActivity::PROPERTY_STUDENT_GROUP,
                    GroupActivity::PROPERTY_TEACHER));

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $groupIdentifier
     * @return string[][]
     */
    public function findEventsForLocation($year, $locationIdentifier)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $locationIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $condition = new EqualityCondition(
                new PropertyConditionVariable(LocationActivity::class_name(), LocationActivity::PROPERTY_LOCATION_ID),
                new StaticConditionVariable((string) $locationIdentifier));

            $activities = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::records(
                LocationActivity::class_name(),
                new RecordRetrievesParameters(
                    null,
                    $condition,
                    null,
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(
                                LocationActivity::class_name(),
                                LocationActivity::PROPERTY_START_TIME)))))->as_array();

            $records = $this->aggregateRecords(
                LocationActivity::class_name(),
                $activities,
                array(LocationActivity::PROPERTY_STUDENT_GROUP, LocationActivity::PROPERTY_TEACHER));

            $cache->save($cacheIdentifier, $records, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
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
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            if ($user->get_official_code())
            {
                $baseTable = $user->is_teacher() ? 'teachers' : 'courses';

                $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) . '_' . $baseTable .
                     '] WHERE person_id = \'' . $user->get_official_code() . '\' AND module_id = \'' . $moduleIdentifier .
                     '\' ORDER BY start_time';
                $statement = DataManager::get_instance()->get_connection()->query($query);
                $resultSet = new ResultSet($statement);
            }
            else
            {
                $resultSet = new ArrayResultSet(array());
            }

            $cache->save($cacheIdentifier, $resultSet, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param User $user
     * @param string $identifier
     * @return string[]
     */
    public function findEventForUserByIdentifier(User $user, $identifier, $year)
    {
        if ($user->get_official_code())
        {
            $baseTable = $user->is_teacher() ? 'teachers' : 'courses';

            $query = 'SELECT TOP 1 * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) . '_' .
                 $baseTable . '] WHERE person_id = \'' . $user->get_official_code() . '\' AND id = \'' . $identifier .
                 '\' ORDER BY start_time';
            $statement = DataManager::get_instance()->get_connection()->query($query);
            return $this->processRecord($statement->fetch(\PDO::FETCH_ASSOC));
        }
        else
        {
            return array();
        }
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
        $query = 'SELECT TOP 1 * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
             '_group_events] WHERE group_id = \'' . $groupIdentifier . '\' AND id = \'' . $eventIdentifier .
             '\' ORDER BY start_time';
        $statement = DataManager::get_instance()->get_connection()->query($query);
        return $this->processRecord($statement->fetch(\PDO::FETCH_ASSOC));
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
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_group_events] WHERE group_id = \'' . $groupIdentifier . '\' AND module_id = \'' . $moduleIdentifier .
                 '\' ORDER BY start_time';
            $statement = DataManager::get_instance()->get_connection()->query($query);
            $resultSet = new ResultSet($statement);

            $cache->save($cacheIdentifier, $resultSet, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
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
        $query = 'SELECT TOP 1 * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
             '_location_events] WHERE location_id = \'' . $locationIdentifier . '\' AND id = \'' . $eventIdentifier .
             '\'';

        $statement = DataManager::get_instance()->get_connection()->query($query);
        return $this->processRecord($statement->fetch(\PDO::FETCH_ASSOC));
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
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_location_events] WHERE location_id = \'' . $locationIdentifier . '\' AND module_id = \'' .
                 $moduleIdentifier . '\' ORDER BY start_time';

            $statement = DataManager::get_instance()->get_connection()->query($query);
            $resultSet = new ResultSet($statement);

            $cache->save($cacheIdentifier, $resultSet, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
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
            $query = 'SELECT DISTINCT department_id, department_code, department_name FROM [INFORDATSYNC].[dbo].[v_syllabus_' .
                 $this->convertYear($year) . '_student_group] WHERE person_id = \'' . $user->get_official_code() . '\'';
            $statement = DataManager::get_instance()->get_connection()->query($query);
            $faculties = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $faculties[$record['department_id']] = $record;
            }

            return $faculties;
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
            $query = 'SELECT DISTINCT group_id, group_name, department_id, department_code, department_name FROM [INFORDATSYNC].[dbo].[v_syllabus_' .
                 $this->convertYear($year) . '_student_group] WHERE person_id = \'' . $user->get_official_code() . '\'';
            $statement = DataManager::get_instance()->get_connection()->query($query);
            $faculties = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $faculties[$record['department_id']][$record['group_id']] = $record;
            }

            return $faculties;
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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $query = 'SELECT DISTINCT department_id, department_code, department_name FROM [INFORDATSYNC].[dbo].[v_syllabus_' .
                 $this->convertYear($year) . '_groups] ORDER BY department_name';
            $statement = DataManager::get_instance()->get_connection()->query($query);

            $departments = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $departments[$record['department_id']] = $record;
            }

            $cache->save($cacheIdentifier, $departments);
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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_groups] ORDER BY department_id, name';
            $statement = DataManager::get_instance()->get_connection()->query($query);

            $groups = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $groups[$record['department_id']][] = $record;
            }

            $cache->save($cacheIdentifier, $groups);
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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_groups] ORDER BY name';
            $statement = DataManager::get_instance()->get_connection()->query($query);

            $groups = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $groups[$record['id']] = $record;
            }

            $cache->save($cacheIdentifier, $groups);
        }

        return $cache->fetch($cacheIdentifier);
    }

    protected function processRecord($record)
    {
        foreach ($record as &$field)
        {
            if (is_resource($field))
            {
                $data = '';

                while (! feof($field))
                {
                    $data .= fread($field, 1024);
                }

                $field = $data;
            }

            if (is_string($field) && ! is_numeric($field))
            {
                $field = iconv('Windows-1252', 'UTF-8', $field);
            }
        }

        return $record;
    }

    /**
     *
     * @param integer $year
     * @return string[]
     */
    public function findZonesByYear($year)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $query = 'SELECT DISTINCT year, zone_id, zone_code, zone_name FROM [INFORDATSYNC].[dbo].[v_syllabus_' .
                 $this->convertYear($year) . '_locations] ORDER BY year, zone_code, zone_name';

            $statement = DataManager::get_instance()->get_connection()->query($query);

            $zones = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $zones[] = $record;
            }

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
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $zoneIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_locations] WHERE zone_id = \'' . $zoneIdentifier . '\' ORDER BY location_code, location_name';
            $statement = DataManager::get_instance()->get_connection()->query($query);

            $locations = array();

            while ($record = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $record = $this->processRecord($record);
                $locations[] = $record;
            }

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
        $query = 'SELECT TOP 1 * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
             '_locations] WHERE year = \'' . $year . '\' AND location_id = \'' . $identifier . '\'';

        $statement = DataManager::get_instance()->get_connection()->query($query);
        return $this->processRecord($statement->fetch(\PDO::FETCH_ASSOC));
    }
}