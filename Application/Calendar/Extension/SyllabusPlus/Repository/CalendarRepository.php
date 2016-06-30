<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Repository;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Cache\Doctrine\Provider\FilesystemCache;
use Chamilo\Libraries\Cache\Doctrine\Provider\PhpFileCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataManager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet;

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
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @param integer $fromDate
     * @param integer $toDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function findEventsForUserAndBetweenDates(User $user, $fromDate, $toDate)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->get_id(), $fromDate, $toDate)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            if ($user->get_official_code())
            {
                $queryParts = array();

                foreach ($this->getYears() as $year)
                {
                    $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                         '_events] WHERE person_id = \'' . $user->get_official_code() . '\'';

                    if (! is_null($fromDate) && ! is_null($toDate))
                    {
                        $query .= 'AND start_time >= ' . $fromDate . ' AND end_time <= ' . $toDate;
                    }

                    $queryParts[] = $query;
                }

                $statement = DataManager::get_instance()->get_connection()->query(implode(' UNION ', $queryParts));
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
     * @param string $groupIdentifier
     * @param integer $fromDate
     * @param integer $toDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function findEventsForGroupAndBetweenDates($year, $groupIdentifier, $fromDate, $toDate)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $groupIdentifier, $fromDate, $toDate)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_group_events] WHERE group_id = \'' . $groupIdentifier . '\'';

            if (! is_null($fromDate) && ! is_null($toDate))
            {
                $query .= 'AND start_time >= ' . $fromDate . ' AND end_time <= ' . $toDate;
            }

            $statement = DataManager::get_instance()->get_connection()->query($query);
            $resultSet = new ResultSet($statement);

            $cache->save($cacheIdentifier, $resultSet, $lifetimeInMinutes * 60);
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
                $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                     '_events] WHERE person_id = \'' . $user->get_official_code() . '\' AND module_id = \'' .
                     $moduleIdentifier . '\'';
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
            $query = 'SELECT TOP 1 * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_events] WHERE person_id = \'' . $user->get_official_code() . '\' AND id = \'' . $identifier . '\'';
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

    /**
     *
     * @param string year
     * @param string $locationIdentifier
     * @param integer $fromDate
     * @param integer $toDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet
     */
    public function findEventsByYearAndLocationAndBetweenDates($year, $locationIdentifier, $fromDate, $toDate)
    {
        $cache = new FilesystemCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $locationIdentifier, $fromDate, $toDate)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration::get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_' . $this->convertYear($year) .
                 '_location_events] WHERE location_id = \'' . $locationIdentifier . '\'';

            if (! is_null($fromDate) && ! is_null($toDate))
            {
                $query .= 'AND start_time >= ' . $fromDate . ' AND end_time <= ' . $toDate;
            }

            $statement = DataManager::get_instance()->get_connection()->query($query);
            $resultSet = new ResultSet($statement);

            $cache->save($cacheIdentifier, $resultSet, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }
}