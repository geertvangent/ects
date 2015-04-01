<?php
namespace Ehb\Core\Metadata\Element\Service;

use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Ehb\Core\Metadata\Schema\Storage\DataClass\Schema;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Core\Metadata\Element\Storage\DataClass\Element;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Ehb\Core\Metadata\Schema\Instance\Storage\DataClass\SchemaInstance;

/**
 *
 * @package Ehb\Core\Metadata\Service
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class ElementService
{

    /**
     *
     * @param Schema $schema
     * @return Element[]
     */
    public function getElementsForSchemaInstance(SchemaInstance $schemaInstance)
    {
        $condition = new ComparisonCondition(
            new PropertyConditionVariable(Element :: class_name(), Element :: PROPERTY_SCHEMA_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($schemaInstance->get_schema_id()));

        return DataManager :: retrieves(
            Element :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(Element :: class_name(), Element :: PROPERTY_DISPLAY_ORDER)))));
    }
}
