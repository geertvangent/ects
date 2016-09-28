<?php
namespace Ehb\Application\Ects\Repository;

use Chamilo\Libraries\Cache\Doctrine\Provider\PhpFileCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectory;
use Ehb\Application\Ects\Storage\DataClass\SubTrajectoryCourse;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Ehb\Application\Ects\Storage\DataClass\Trajectory;

/**
 *
 * @package Ehb\Application\Ects\Repository
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class EctsRepository
{
    const LIFETIME_IN_MINUTES = 60;

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
     * @return string
     */
    public function findYears()
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new ComparisonCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                ComparisonCondition::GREATER_THAN_OR_EQUAL,
                new StaticConditionVariable('2007-08'));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            $years = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    Training::PROPERTY_YEAR,
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                            SORT_DESC))));

            $cache->save($cacheIdentifier, $years, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $year
     * @return string[][]
     */
    public function findFacultiesForYear($year)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));
            $conditions[] = new NotCondition(
                new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    null));

            $faculties = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(Training::PROPERTY_FACULTY_ID, Training::PROPERTY_FACULTY),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $faculties, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $year
     * @param string $facultyIdentifier
     * @return string[][]
     */
    public function findTypesForYearAndFacultyIdentifier($year, $facultyIdentifier = null)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $facultyIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            if (! is_null($facultyIdentifier))
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    new StaticConditionVariable($facultyIdentifier));
            }

            $conditions[] = new NotCondition(
                new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    null));

            $conditions[] = new NotCondition(
                new InCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE_ID),
                    array(14, 16, 18)));

            $types = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(Training::PROPERTY_TYPE_ID, Training::PROPERTY_TYPE),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $types, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    public function findTrainingsForYearFacultyIdentifierTypeIdentifierAndText($year, $facultyIdentifier = null,
        $typeIdentifier = null, $text = null)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year, $facultyIdentifier, $typeIdentifier, $text)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            if (! is_null($facultyIdentifier))
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    new StaticConditionVariable($facultyIdentifier));
            }

            if (! is_null($typeIdentifier))
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE_ID),
                    new StaticConditionVariable($typeIdentifier));
            }

            if (! is_null($text))
            {
                $conditions[] = new PatternMatchCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_NAME),
                    '*' . $text . '*');
            }

            $conditions[] = new NotCondition(
                new EqualityCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY_ID),
                    null));

            $conditions[] = new NotCondition(
                new InCondition(
                    new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE_ID),
                    array(14, 16, 18)));

            $types = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(
                        Training::PROPERTY_ID,
                        Training::PROPERTY_NAME,
                        Training::PROPERTY_FACULTY_ID,
                        Training::PROPERTY_FACULTY,
                        Training::PROPERTY_TYPE_ID,
                        Training::PROPERTY_TYPE),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_NAME),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $types, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param string $trainingIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Training
     */
    public function findTrainingByIdentifier($trainingIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            Training::class_name(),
            $trainingIdentifier);
    }

    /**
     *
     * @param integer $trainingIdentifier
     * @return string[][]
     */
    public function findTrajectoriesForTrainingIdentifier($trainingIdentifier)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $trainingIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Trajectory::class_name(), Trajectory::PROPERTY_TRAINING_ID),
                new StaticConditionVariable($trainingIdentifier));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Trajectory::class_name(), Trajectory::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            $trajectories = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Trajectory::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(Trajectory::PROPERTY_ID, Trajectory::PROPERTY_NAME, Trajectory::PROPERTY_SORT),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(Trajectory::class_name(), Trajectory::PROPERTY_SORT),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $trajectories, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return string[][]
     */
    public function findSubTrajectoriesForTrajectoryIdentifier($trajectoryIdentifier)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $trajectoryIdentifier)));

        if (! $cache->contains($cacheIdentifier))
        {
            $conditions = array();

            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(SubTrajectory::class_name(), SubTrajectory::PROPERTY_TRAJECTORY_ID),
                new StaticConditionVariable($trajectoryIdentifier));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(SubTrajectory::class_name(), SubTrajectory::PROPERTY_INVISIBLE),
                new StaticConditionVariable(0));

            $trajectories = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                SubTrajectory::class_name(),
                new DataClassDistinctParameters(
                    new AndCondition($conditions),
                    array(SubTrajectory::PROPERTY_ID, SubTrajectory::PROPERTY_NAME, SubTrajectory::PROPERTY_SORT),
                    null,
                    array(
                        new OrderBy(
                            new PropertyConditionVariable(SubTrajectory::class_name(), SubTrajectory::PROPERTY_SORT),
                            SORT_ASC))));

            $cache->save($cacheIdentifier, $trajectories, self::LIFETIME_IN_MINUTES * 60);
        }

        return $cache->fetch($cacheIdentifier);
    }

    /**
     *
     * @param integer $subTrajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\SubTrajectory
     */
    public function findSubTrajectoryByIdentifier($subTrajectoryIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            SubTrajectory::class_name(),
            $subTrajectoryIdentifier);
    }

    /**
     *
     * @param integer $trajectoryIdentifier
     * @return \Ehb\Application\Ects\Storage\DataClass\Trajectory
     */
    public function findTrajectoryByIdentifier($trajectoryIdentifier)
    {
        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::retrieve_by_id(
            Trajectory::class_name(),
            $trajectoryIdentifier);
    }

    public function findSubTrajectoryCoursesForSubTrajectoryIdentifier($subTrajectoryIdentifier)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                SubTrajectoryCourse::class_name(),
                SubTrajectoryCourse::PROPERTY_SUB_TRAJECTORY_ID),
            new StaticConditionVariable($subTrajectoryIdentifier));

        return \Ehb\Libraries\Storage\DataManager\Administration\DataManager::records(
            SubTrajectoryCourse::class_name(),
            new RecordRetrievesParameters(
                null,
                $condition,
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(
                            SubTrajectoryCourse::class_name(),
                            SubTrajectoryCourse::PROPERTY_PARENT_PROGRAMME_ID),
                        new PropertyConditionVariable(
                            SubTrajectoryCourse::class_name(),
                            SubTrajectoryCourse::PROPERTY_NAME)))));
    }
}