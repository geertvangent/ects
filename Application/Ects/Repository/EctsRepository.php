<?php
namespace Ehb\Application\Ects\Repository;

use Chamilo\Libraries\Cache\Doctrine\Provider\PhpFileCache;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;

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

    public function findYears()
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__)));

        if (! $cache->contains($cacheIdentifier))
        {
            $condition = new ComparisonCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                ComparisonCondition::GREATER_THAN_OR_EQUAL,
                new StaticConditionVariable('2007-08'));

            $years = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    $condition,
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
     * @return \Ehb\Application\Ects\Storage\DataClass\Faculty[]
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
     * @return string[]
     */
    public function findTypesForYear($year)
    {
        $cache = new PhpFileCache(Path::getInstance()->getCachePath(__NAMESPACE__));
        $cacheIdentifier = md5(serialize(array(__METHOD__, $year)));

        if (! $cache->contains($cacheIdentifier))
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                new StaticConditionVariable($year));

            $types = \Ehb\Libraries\Storage\DataManager\Administration\DataManager::distinct(
                Training::class_name(),
                new DataClassDistinctParameters(
                    $condition,
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
}