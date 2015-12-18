<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Repository;

use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataManager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\ResultSet;
use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;
use Chamilo\Libraries\Cache\Doctrine\Provider\FilesystemCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Cache\Doctrine\Provider\PhpFileCache;

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
        if (is_null(static :: $instance))
        {
            self :: $instance = new static();
        }

        return static :: $instance;
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
        $cache = new FilesystemCache(Path :: getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->get_id())));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration :: get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            if ($user->get_official_code())
            {
                $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_events] WHERE person_id = \'' .
                     $user->get_official_code() . '\'';
                $statement = DataManager :: get_instance()->get_connection()->query($query);
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
    public function findEventsForGroupAndBetweenDates($groupIdentifier, $fromDate, $toDate)
    {
        $cache = new FilesystemCache(Path :: getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $groupIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration :: get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_group_events] WHERE group_id = \'' .
                 $groupIdentifier . '\'';

            $statement = DataManager :: get_instance()->get_connection()->query($query);
            $resultSet = new ResultSet($statement);

            $cache->save($cacheIdentifier, $resultSet, $lifetimeInMinutes * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @param string $moduleIdentifier
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findEventsForUserByModuleIdentifier(User $user, $moduleIdentifier)
    {
        $cache = new FilesystemCache(Path :: getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $user->get_id(), $moduleIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $lifetimeInMinutes = Configuration :: get_instance()->get_setting(
                array('Chamilo\Libraries\Calendar', 'refresh_external'));

            if ($user->get_official_code())
            {
                $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_events] WHERE person_id = \'' .
                     $user->get_official_code() . '\' AND module_id = \'' . $moduleIdentifier . '\'';
                $statement = DataManager :: get_instance()->get_connection()->query($query);
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
    public function findEventForUserByIdentifier(User $user, $identifier)
    {
        if ($user->get_official_code())
        {
            $query = 'SELECT TOP 1 * FROM [INFORDATSYNC].[dbo].[v_syllabus_events] WHERE person_id = \'' .
                 $user->get_official_code() . '\' AND id = \'' . $identifier . '\'';
            $statement = DataManager :: get_instance()->get_connection()->query($query);
            return $statement->fetch(\PDO :: FETCH_ASSOC);
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
    public function findFacultiesForUser(User $user)
    {
        if ($user->get_official_code())
        {
            $query = 'SELECT DISTINCT department_id, department_code, department_name FROM [INFORDATSYNC].[dbo].[v_syllabus_student_group] WHERE person_id = \'' .
                 $user->get_official_code() . '\'';
            $statement = DataManager :: get_instance()->get_connection()->query($query);
            $faculties = array();

            while ($record = $statement->fetch(\PDO :: FETCH_ASSOC))
            {
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
    public function findFacultiesGroupsForUser(User $user)
    {
        if ($user->get_official_code())
        {
            $query = 'SELECT DISTINCT group_id, group_name, department_id, department_code, department_name FROM [INFORDATSYNC].[dbo].[v_syllabus_student_group] WHERE person_id = \'' .
                 $user->get_official_code() . '\'';
            $statement = DataManager :: get_instance()->get_connection()->query($query);
            $faculties = array();

            while ($record = $statement->fetch(\PDO :: FETCH_ASSOC))
            {
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
     * @return string[]
     */
    public function findFaculties()
    {
        $cache = new PhpFileCache(Path :: getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__)));

        if (! $cache->contains($cacheIdentifier))
        {
            $query = 'SELECT DISTINCT department_id, department_code, department_name FROM [INFORDATSYNC].[dbo].[v_syllabus_groups] ORDER BY department_name';
            $statement = DataManager :: get_instance()->get_connection()->query($query);

            $departments = array();

            while ($record = $statement->fetch(\PDO :: FETCH_ASSOC))
            {
                $departments[$record['department_id']] = $record;
            }

            $cache->save($cacheIdentifier, $departments);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @return string[]
     */
    public function findFacultyGroups()
    {
        $cache = new PhpFileCache(Path :: getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__)));

        if (! $cache->contains($cacheIdentifier))
        {

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_groups] ORDER BY department_id, name';
            $statement = DataManager :: get_instance()->get_connection()->query($query);

            $groups = array();

            while ($record = $statement->fetch(\PDO :: FETCH_ASSOC))
            {
                $groups[$record['department_id']][] = $record;
            }

            $cache->save($cacheIdentifier, $groups);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @return string[]
     */
    public function findGroups()
    {
        $cache = new PhpFileCache(Path :: getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__)));

        if (! $cache->contains($cacheIdentifier))
        {

            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_syllabus_groups] ORDER BY name';
            $statement = DataManager :: get_instance()->get_connection()->query($query);

            $groups = array();

            while ($record = $statement->fetch(\PDO :: FETCH_ASSOC))
            {
                $groups[$record['id']] = $record;
            }

            $cache->save($cacheIdentifier, $groups);
        }

        return $cache->fetch($cacheIdentifier);
    }
}