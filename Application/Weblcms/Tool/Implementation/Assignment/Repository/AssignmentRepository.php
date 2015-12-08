<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository;

use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableColumnModel;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\DataClass\Property\DataClassProperties;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\GroupBy;
use Chamilo\Libraries\Storage\Query\Join;
use Chamilo\Libraries\Storage\Query\Joins;
use Chamilo\Libraries\Storage\Query\Variable\FixedPropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\FunctionConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Feedback;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class AssignmentRepository
{

    /**
     *
     * @param integer $publicationIdentifier
     * @return integer
     */
    public function countEntriesForPublicationIdentifier($publicationIdentifier)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
            new StaticConditionVariable($publicationIdentifier));

        return DataManager :: count(Entry :: class_name(), new DataClassCountParameters($condition));
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param OrderBy[] $orderBy
     * @return boolean
     */
    public function findTargetUsersForPublication(ContentObjectPublication $publication, $condition, $offset, $count,
        $orderBy)
    {
        $users = \Chamilo\Application\Weblcms\Storage\DataManager :: retrieve_publication_target_users(
            $publication->getId(),
            $publication->get_course_id(),
            null,
            null,
            null,
            $condition)->as_array();

        $user_ids = array();

        foreach ($users as $user)
        {
            $user_ids[$user->get_id()] = $user->get_id();
        }

        if (count($user_ids) < 1)
        {
            $user_ids[] = - 1;
        }

        $conditions = array();

        ! is_null($condition) ? $conditions[] = $condition : $condition;

        $conditions[] = new InCondition(
            new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID),
            $user_ids);

        $condition = new AndCondition($conditions);

        $properties = new DataClassProperties();
        $properties->add(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_FIRSTNAME));
        $properties->add(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_LASTNAME));

        $baseClass = User :: class_name();
        $baseVariable = new PropertyConditionVariable($baseClass, $baseClass :: PROPERTY_ID);

        return $this->findTargetsForEntityTypeAndPublication(
            Entry :: ENTITY_TYPE_USER,
            $publication,
            $condition,
            $offset,
            $count,
            $orderBy,
            $properties,
            $baseClass,
            $baseVariable);
    }

    /**
     *
     * @param integer $entityType
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param OrderBy[] $orderBy
     * @param DataClassProperties $properties
     * @param string $baseClass
     * @param PropertyConditionVariable $baseVariable
     * @return boolean
     */
    private function findTargetsForEntityTypeAndPublication($entityType, ContentObjectPublication $publication,
        $condition, $offset, $count, $orderBy, DataClassProperties $properties, $baseClass, $baseVariable)
    {
        $properties->add(
            new FixedPropertyConditionVariable($baseClass, $baseClass :: PROPERTY_ID, Entry :: PROPERTY_ENTITY_ID));

        $submittedVariable = new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_SUBMITTED);

        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable :: MIN,
                $submittedVariable,
                EntityTableColumnModel :: PROPERTY_FIRST_ENTRY_DATE));

        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable :: MAX,
                $submittedVariable,
                EntityTableColumnModel :: PROPERTY_LAST_ENTRY_DATE));

        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable :: COUNT,
                $submittedVariable,
                EntityTableColumnModel :: PROPERTY_ENTRY_COUNT));

        $joins = new Joins();

        $joinConditions = array();

        $joinConditions[] = new EqualityCondition(
            $baseVariable,
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_ID));

        $joinConditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
            new StaticConditionVariable($publication->getId()));

        $joinConditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable($entityType));

        $joinCondition = new AndCondition($joinConditions);

        $joins->add(new Join(Entry :: class_name(), $joinCondition, Join :: TYPE_LEFT));

        $group_by = new GroupBy();
        $group_by->add($baseVariable);

        $parameters = new RecordRetrievesParameters(
            $properties,
            $condition,
            $count,
            $offset,
            $orderBy,
            $joins,
            $group_by);

        return DataManager :: records($baseClass, $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @return integer
     */
    public function countFeedbackForPublicationByEntityTypeAndEntityId(ContentObjectPublication $publication,
        $entityType, $entityId)
    {
        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_PUBLICATION_ID),
            new StaticConditionVariable($publication->getId()));

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable($entityType));

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ENTITY_ID),
            new StaticConditionVariable($entityId));

        $condition = new AndCondition($conditions);

        $joins = new Joins();
        $joins->add(
            new Join(
                Feedback :: class_name(),
                new EqualityCondition(
                    new PropertyConditionVariable(Entry :: class_name(), Entry :: PROPERTY_ID),
                    new PropertyConditionVariable(Feedback :: class_name(), Feedback :: PROPERTY_ENTRY_ID))));

        $parameters = new DataClassCountParameters($condition, $joins);

        return DataManager :: count(Entry :: class_name(), $parameters);
    }
}