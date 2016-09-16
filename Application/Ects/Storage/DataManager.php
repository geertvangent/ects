<?php
namespace Ehb\Application\Ects\Storage;

use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Ects\Storage\DataClass\Faculty;
use Ehb\Application\Ects\Storage\DataClass\Training;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\OrderBy;

/**
 *
 * @package Ehb\Application\Ects\Storage
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class DataManager extends \Ehb\Libraries\Storage\DataManager\Administration\DataManager
{

    /**
     *
     * @return string[]
     */
    public static function getYears()
    {
        $condition = new ComparisonCondition(
            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
            ComparisonCondition::GREATER_THAN_OR_EQUAL,
            new StaticConditionVariable('2007-08'));

        return self::distinct(
            Training::class_name(),
            new DataClassDistinctParameters(
                $condition,
                Training::PROPERTY_YEAR,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
                        SORT_DESC))));
    }

    /**
     *
     * @param string $year
     * @return \Ehb\Application\Ects\Storage\DataClass\Faculty[]
     */
    public static function getFacultiesForYear($year)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
            new StaticConditionVariable($year));

        return self::distinct(
            Training::class_name(),
            new DataClassDistinctParameters(
                $condition,
                array(Training::PROPERTY_FACULTY_ID, Training::PROPERTY_FACULTY),
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_FACULTY),
                        SORT_ASC))));
    }

    /**
     *
     * @return string[]
     */
    public static function getTypesForYear($year)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_YEAR),
            new StaticConditionVariable($year));

        return self::distinct(
            Training::class_name(),
            new DataClassDistinctParameters(
                $condition,
                array(Training::PROPERTY_TYPE_ID, Training::PROPERTY_TYPE),
                null,
                array(
                    new OrderBy(new PropertyConditionVariable(Training::class_name(), Training::PROPERTY_TYPE), SORT_ASC))));
    }
}
