<?php
namespace Ehb\Core\Metadata\Service;

use Ehb\Core\Metadata\Relation\Service\RelationService;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Ehb\Core\Metadata\Relation\Storage\DataClass\Relation;
use Chamilo\Libraries\Storage\Query\Condition\ComparisonCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Ehb\Core\Metadata\Relation\Instance\Storage\DataClass\RelationInstance;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Ehb\Core\Metadata\Schema\Storage\DataClass\Schema;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Core\Metadata\Vocabulary\Storage\DataClass\Vocabulary;
use Ehb\Core\Metadata\Element\Storage\DataClass\Element;

/**
 *
 * @package Ehb\Core\Metadata\Service
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class EntityService
{
    const PROPERTY_METADATA_SCHEMA = 'schema';
    const PROPERTY_METADATA_SCHEMA_NEW = 'new';
    const PROPERTY_METADATA_SCHEMA_EXISTING = 'existing';

    /**
     *
     * @param \Ehb\Core\Metadata\Relation\Service\RelationService $relationService
     * @param \Chamilo\Libraries\Storage\DataClass\DataClass $entity
     * @return integer[]
     */
    public function getAvailableSchemasForEntity(RelationService $relationService, DataClass $entity)
    {
        $schemaIds = $this->getSourceRelationIdsForEntity(
            Schema :: class_name(),
            $relationService->getRelationByName('isAvailableFor'),
            $entity);

        return DataManager :: retrieves(
            Schema :: class_name(),
            new DataClassRetrievesParameters(
                new InCondition(new PropertyConditionVariable(Schema :: class_name(), Schema :: PROPERTY_ID), $schemaIds)));
    }

    /**
     *
     * @param string $sourceType
     * @param \Ehb\Core\Metadata\Relation\Storage\DataClass\Relation $relation
     * @param \Chamilo\Libraries\Storage\DataClass\DataClass $targetEntity
     * @return integer[]
     */
    public function getSourceRelationIdsForEntity($sourceType, Relation $relation, DataClass $targetEntity)
    {
        $conditions = array();
        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(RelationInstance :: class_name(), RelationInstance :: PROPERTY_SOURCE_TYPE),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($sourceType));
        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(RelationInstance :: class_name(), RelationInstance :: PROPERTY_RELATION_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($relation->get_id()));
        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(RelationInstance :: class_name(), RelationInstance :: PROPERTY_TARGET_TYPE),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($targetEntity->class_name()));
        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(RelationInstance :: class_name(), RelationInstance :: PROPERTY_TARGET_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($targetEntity->get_id()));

        $condition = new AndCondition($conditions);

        return DataManager :: distinct(
            RelationInstance :: class_name(),
            new DataClassDistinctParameters($condition, RelationInstance :: PROPERTY_SOURCE_ID));
    }

    public function getVocabularyByElementIdAndUserId(Element $element, User $user)
    {
        $conditions = array();
        $conditions[] = new ComparisonCondition(
            new PropertyConditionVariable(Vocabulary :: class_name(), Vocabulary :: PROPERTY_ELEMENT_ID),
            ComparisonCondition :: EQUAL,
            new StaticConditionVariable($element->get_id()));

        return DataManager :: retrieves(
            Vocabulary :: class_name(),
            new DataClassRetrievesParameters(new AndCondition($conditions)));
    }
}
