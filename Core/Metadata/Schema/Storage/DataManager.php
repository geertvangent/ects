<?php
namespace Ehb\Core\Metadata\Schema\Storage;

use Ehb\Core\Metadata\Schema\Storage\DataClass\Schema;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 *
 * @package Ehb\Core\Metadata\Schema\Storage
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'metadata_';

    /**
     * Retrieves a metadata schema by a given namespace
     *
     * @param string $namespace
     * @return \Ehb\Core\Metadata\Schema\Storage\DataClass\Schema
     */
    public static function retrieve_schema_by_namespace($namespace)
    {
        $condition = new ComparisonCondition(
            new PropertyConditionVariable(Schema :: class_name(), Schema :: PROPERTY_NAMESPACE),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($namespace));

        $schema = self :: retrieve(Schema :: class_name(), $condition);

        if (! $schema)
        {
            throw new \InvalidArgumentException('The given namespace ' . $namespace . ' is invalid');
        }

        return $schema;
    }
}