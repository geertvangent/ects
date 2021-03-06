<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository;

use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider;
use Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableColumnModel;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\DataClass\Property\DataClassProperties;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrieveParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\Condition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\GroupBy;
use Chamilo\Libraries\Storage\Query\Join;
use Chamilo\Libraries\Storage\Query\Joins;
use Chamilo\Libraries\Storage\Query\Variable\FixedPropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\FunctionConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Feedback;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Note;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Score;

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
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publicationIdentifier));
        
        return DataManager::count(Entry::class_name(), new DataClassCountParameters($condition));
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param OrderBy[] $orderBy
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findTargetUsersForPublication(ContentObjectPublication $publication, $condition, $offset, $count, 
        $orderBy)
    {
        $users = $this->retrievePublicationTargetUsers($publication, $condition)->as_array();
        
        $properties = new DataClassProperties();
        $properties->add(new PropertyConditionVariable(User::class_name(), User::PROPERTY_FIRSTNAME));
        $properties->add(new PropertyConditionVariable(User::class_name(), User::PROPERTY_LASTNAME));
        
        $baseClass = User::class_name();
        
        return $this->findTargetsForEntityTypeAndPublication(
            Entry::ENTITY_TYPE_USER, 
            $publication, 
            $this->getTargetEntitiesCondition(User::class_name(), $users, $condition), 
            $offset, 
            $count, 
            $orderBy, 
            $properties, 
            $baseClass, 
            $this->getTargetBaseVariable($baseClass));
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return integer
     */
    public function countTargetUsersForPublication(ContentObjectPublication $publication, $condition)
    {
        return $this->retrievePublicationTargetUsers($publication, $condition)->size();
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    private function retrievePublicationTargetUsers(ContentObjectPublication $publication, Condition $condition = null)
    {
        return \Chamilo\Application\Weblcms\Storage\DataManager::retrieve_publication_target_users(
            $publication->getId(), 
            $publication->get_course_id(), 
            null, 
            null, 
            null, 
            $condition);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param OrderBy[] $orderBy
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findTargetCourseGroupsForPublication(ContentObjectPublication $publication, $condition, $offset, 
        $count, $orderBy)
    {
        $courseGroups = $this->retrievePublicationTargetCourseGroups($publication, $condition)->as_array();
        
        $properties = new DataClassProperties();
        $properties->add(new PropertyConditionVariable(CourseGroup::class_name(), CourseGroup::PROPERTY_NAME));
        
        $baseClass = CourseGroup::class_name();
        
        return $this->findTargetsForEntityTypeAndPublication(
            Entry::ENTITY_TYPE_COURSE_GROUP, 
            $publication, 
            $this->getTargetEntitiesCondition(CourseGroup::class_name(), $courseGroups, $condition), 
            $offset, 
            $count, 
            $orderBy, 
            $properties, 
            $baseClass, 
            $this->getTargetBaseVariable($baseClass));
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return integer
     */
    public function countTargetCourseGroupsForPublication(ContentObjectPublication $publication, $condition)
    {
        return $this->retrievePublicationTargetCourseGroups($publication, $condition)->size();
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    private function retrievePublicationTargetCourseGroups(ContentObjectPublication $publication, 
        Condition $condition = null)
    {
        return \Chamilo\Application\Weblcms\Storage\DataManager::retrieve_publication_target_course_groups(
            $publication->getId(), 
            $publication->get_course_id(), 
            null, 
            null, 
            null, 
            $condition);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param OrderBy[] $orderBy
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findTargetGroupsForPublication(ContentObjectPublication $publication, $condition, $offset, $count, 
        $orderBy)
    {
        $platformGroups = $this->retrievePublicationTargetPlatformGroups($publication, $condition)->as_array();
        
        $properties = new DataClassProperties();
        $properties->add(new PropertyConditionVariable(Group::class_name(), Group::PROPERTY_NAME));
        
        $baseClass = Group::class_name();
        
        return $this->findTargetsForEntityTypeAndPublication(
            Entry::ENTITY_TYPE_GROUP, 
            $publication, 
            $this->getTargetEntitiesCondition(Group::class_name(), $platformGroups, $condition), 
            $offset, 
            $count, 
            $orderBy, 
            $properties, 
            $baseClass, 
            $this->getTargetBaseVariable($baseClass));
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return integer
     */
    public function countTargetGroupsForPublication(ContentObjectPublication $publication, $condition)
    {
        return $this->retrievePublicationTargetPlatformGroups($publication, $condition)->size();
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    private function retrievePublicationTargetPlatformGroups(ContentObjectPublication $publication, 
        Condition $condition = null)
    {
        return \Chamilo\Application\Weblcms\Storage\DataManager::retrieve_publication_target_platform_groups(
            $publication->getId(), 
            $publication->get_course_id(), 
            null, 
            null, 
            null, 
            $condition);
    }

    /**
     *
     * @param string $entityClass
     * @param \Chamilo\Libraries\Storage\DataClass\DataClass[] $entities
     * @param Condition $condition
     */
    private function getTargetEntitiesCondition($entityClass, $entities, Condition $condition = null)
    {
        $entityIds = array();
        
        foreach ($entities as $entity)
        {
            $entityIds[$entity->get_id()] = $entity->get_id();
        }
        
        if (count($entityIds) < 1)
        {
            $entityIds[] = - 1;
        }
        
        $conditions = array();
        
        ! is_null($condition) ? $conditions[] = $condition : $condition;
        
        $conditions[] = new InCondition(new PropertyConditionVariable($entityClass, DataClass::PROPERTY_ID), $entityIds);
        
        return new AndCondition($conditions);
    }

    /**
     *
     * @param string $baseClass
     * @return \Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable
     */
    private function getTargetBaseVariable($baseClass)
    {
        return new PropertyConditionVariable($baseClass, $baseClass::PROPERTY_ID);
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
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    private function findTargetsForEntityTypeAndPublication($entityType, ContentObjectPublication $publication, 
        $condition, $offset, $count, $orderBy, DataClassProperties $properties, $baseClass, $baseVariable)
    {
        $properties->add(
            new FixedPropertyConditionVariable($baseClass, $baseClass::PROPERTY_ID, Entry::PROPERTY_ENTITY_ID));
        $properties->add(new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_TYPE));
        
        $submittedVariable = new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_SUBMITTED);
        
        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable::MIN, 
                $submittedVariable, 
                EntityTableColumnModel::PROPERTY_FIRST_ENTRY_DATE));
        
        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable::MAX, 
                $submittedVariable, 
                EntityTableColumnModel::PROPERTY_LAST_ENTRY_DATE));
        
        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable::COUNT, 
                $submittedVariable, 
                EntityTableColumnModel::PROPERTY_ENTRY_COUNT));
        
        $joins = new Joins();
        
        $joinConditions = array();
        
        $joinConditions[] = new EqualityCondition(
            $baseVariable, 
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID));
        
        $joinConditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publication->getId()));
        
        $joinConditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_TYPE), 
            new StaticConditionVariable($entityType));
        
        $joinCondition = new AndCondition($joinConditions);
        
        $joins->add(new Join(Entry::class_name(), $joinCondition, Join::TYPE_LEFT));
        
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
        
        return DataManager::records($baseClass, $parameters);
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
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publication->getId()));
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_TYPE), 
            new StaticConditionVariable($entityType));
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID), 
            new StaticConditionVariable($entityId));
        
        $condition = new AndCondition($conditions);
        
        $joins = new Joins();
        $joins->add(
            new Join(
                Feedback::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID), 
                    new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID))));
        
        $parameters = new DataClassCountParameters($condition, $joins);
        
        return DataManager::count(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @return AndCondition
     */
    private function getPublicationAndEntityTypeCondition(ContentObjectPublication $publication, $entityType)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publication->get_id()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_TYPE), 
            new StaticConditionVariable($entityType));
        return new AndCondition($conditions);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @return integer
     */
    public function countDistinctEntriesByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        $property = new FunctionConditionVariable(
            FunctionConditionVariable::DISTINCT, 
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID));
        
        $parameters = new DataClassCountParameters(
            $this->getPublicationAndEntityTypeCondition($publication, $entityType), 
            null, 
            $property);
        
        return DataManager::count(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @return integer
     */
    public function countDistinctFeedbackByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        $joins = new Joins();
        
        $joins->add(
            new Join(
                Feedback::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID), 
                    new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID))));
        
        $property = new FunctionConditionVariable(
            FunctionConditionVariable::DISTINCT, 
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID));
        
        $parameters = new DataClassCountParameters(
            $this->getPublicationAndEntityTypeCondition($publication, $entityType), 
            $joins, 
            $property);
        
        return DataManager::count(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @return integer
     */
    public function countDistinctLateEntriesByPublicationAndEntityType(ContentObjectPublication $publication, 
        $entityType)
    {
        $joins = new Joins();
        
        $joins->add(
            new Join(
                ContentObjectPublication::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication::class_name(), 
                        ContentObjectPublication::PROPERTY_ID), 
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID))));
        
        $property = new FunctionConditionVariable(
            FunctionConditionVariable::DISTINCT, 
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID));
        
        $conditions = array();
        $conditions[] = $this->getPublicationAndEntityTypeCondition($publication, $entityType);
        $conditions[] = new InequalityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_SUBMITTED), 
            InequalityCondition::GREATER_THAN, 
            new StaticConditionVariable($publication->get_content_object()->get_end_time()));
        $condition = new AndCondition($conditions);
        
        $parameters = new DataClassCountParameters($condition, $joins, $property);
        
        return DataManager::count(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @return AndCondition
     */
    protected function getPublicationEntityTypeAndIdCondition(ContentObjectPublication $publication, $entityType, 
        $entityId)
    {
        $conditions = array();
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publication->get_id()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_TYPE), 
            new StaticConditionVariable($entityType));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID), 
            new StaticConditionVariable($entityId));
        
        return new AndCondition($conditions);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @param \Chamilo\Libraries\Storage\Query\Condition\Condition $condition
     * @return integer
     */
    public function countEntriesForPublicationEntityTypeAndId(ContentObjectPublication $publication, $entityType, 
        $entityId, $condition)
    {
        $conditions = array();
        
        if ($condition instanceof Condition)
        {
            $conditions[] = $condition;
        }
        
        $conditions[] = $this->getPublicationEntityTypeAndIdCondition($publication, $entityType, $entityId);
        
        $condition = new AndCondition($conditions);
        
        return DataManager::count(Entry::class_name(), new DataClassCountParameters($condition));
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @return integer
     */
    public function countDistinctFeedbackForEntityTypeAndId(ContentObjectPublication $publication, $entityType, 
        $entityId)
    {
        $joins = new Joins();
        
        $joins->add(
            new Join(
                Feedback::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID), 
                    new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID))));
        
        $parameters = new DataClassCountParameters(
            $this->getPublicationEntityTypeAndIdCondition($publication, $entityType, $entityId), 
            $joins);
        
        return DataManager::count(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @return integer
     */
    public function countDistinctScoreForEntityTypeAndId(ContentObjectPublication $publication, $entityType, $entityId)
    {
        $joins = new Joins();
        
        $joins->add(
            new Join(
                Score::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID), 
                    new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_ENTRY_ID))));
        
        $parameters = new DataClassCountParameters(
            $this->getPublicationEntityTypeAndIdCondition($publication, $entityType, $entityId), 
            $joins);
        
        return DataManager::count(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @return integer
     */
    public function retrieveAverageScoreForEntityTypeAndId(ContentObjectPublication $publication, $entityType, $entityId)
    {
        $joins = new Joins();
        
        $joins->add(
            new Join(
                Score::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID), 
                    new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_ENTRY_ID))));
        
        $properties = new DataClassProperties();
        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable::AVERAGE, 
                new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_SCORE), 
                AssignmentDataProvider::AVERAGE_SCORE));
        
        $parameters = new RecordRetrieveParameters(
            $properties, 
            $this->getPublicationEntityTypeAndIdCondition($publication, $entityType, $entityId), 
            array(), 
            $joins);
        
        return DataManager::record(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @param \Chamilo\Libraries\Storage\Query\Condition\Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param \Chamilo\Libraries\Storage\Query\OrderBy[] $orderProperty
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    public function retrieveEntriesForPublicationEntityTypeAndId(ContentObjectPublication $publication, $entityType, 
        $entityId, $condition, $offset, $count, $orderProperty)
    {
        $conditions = array();
        
        if ($condition instanceof Condition)
        {
            $conditions[] = $condition;
        }
        
        $conditions[] = $this->getPublicationEntityTypeAndIdCondition($publication, $entityType, $entityId);
        
        $condition = new AndCondition($conditions);
        
        $joins = new Joins();
        
        $joins->add(
            new Join(
                User::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(User::class_name(), User::PROPERTY_ID), 
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_USER_ID))));
        
        $joins->add(
            new Join(
                ContentObject::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(ContentObject::class_name(), ContentObject::PROPERTY_ID), 
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_CONTENT_OBJECT_ID))));
        
        $joins->add(
            new Join(
                Score::class_name(), 
                new EqualityCondition(
                    new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_ENTRY_ID), 
                    new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID)), 
                Join::TYPE_LEFT));
        
        $properties = new DataClassProperties();
        
        $properties->add(new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID));
        $properties->add(new PropertyConditionVariable(User::class_name(), User::PROPERTY_LASTNAME));
        $properties->add(new PropertyConditionVariable(User::class_name(), User::PROPERTY_FIRSTNAME));
        
        $properties->add(new PropertyConditionVariable(ContentObject::class_name(), ContentObject::PROPERTY_TITLE));
        $properties->add(
            new PropertyConditionVariable(ContentObject::class_name(), ContentObject::PROPERTY_DESCRIPTION));
        $properties->add(new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_SUBMITTED));
        $properties->add(new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_SCORE));
        $properties->add(new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_CONTENT_OBJECT_ID));
        $properties->add(new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_USER_ID));
        $properties->add(new PropertyConditionVariable(ContentObject::class_name(), ContentObject::PROPERTY_TYPE));
        
        $parameters = new RecordRetrievesParameters($properties, $condition, $count, $offset, $orderProperty, $joins);
        
        return DataManager::records(Entry::class_name(), $parameters);
    }

    /**
     *
     * @param integer $entryIdentifier
     * @return integer
     */
    public function countFeedbackByEntryIdentifier($entryIdentifier)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID), 
            new StaticConditionVariable($entryIdentifier));
        
        return DataManager::count(Feedback::class_name(), new DataClassCountParameters($condition));
    }

    /**
     *
     * @param integer $entryIdentifier
     * @return \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry
     */
    public function retrieveEntryByIdentifier($entryIdentifier)
    {
        return DataManager::retrieve_by_id(Entry::class_name(), $entryIdentifier);
    }

    /**
     *
     * @param integer[] $entryIdentifiers[]
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    public function retrieveEntriesByIdentifiers($entryIdentifiers)
    {
        $condition = new InCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ID), 
            $entryIdentifiers);
        
        return DataManager::retrieves(Entry::class_name(), new DataClassRetrievesParameters($condition));
    }

    /**
     *
     * @param \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry $entry
     * @return Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Score
     */
    public function retrieveScoreByEntry(Entry $entry)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Score::class_name(), Score::PROPERTY_ENTRY_ID), 
            new StaticConditionVariable($entry->getId()));
        
        $score = DataManager::retrieve(Score::class_name(), new DataClassRetrieveParameters($condition));
        
        if ($score instanceof Score)
        {
            return $score;
        }
        else
        {
            return null;
        }
    }

    /**
     *
     * @param \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry $entry
     * @return Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Note
     */
    public function retrieveNoteByEntry(Entry $entry)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Note::class_name(), Note::PROPERTY_ENTRY_ID), 
            new StaticConditionVariable($entry->getId()));
        
        $note = DataManager::retrieve(Note::class_name(), new DataClassRetrieveParameters($condition));
        
        if ($note instanceof Note)
        {
            return $note;
        }
        else
        {
            return null;
        }
    }

    /**
     *
     * @param integer $feedbackIdentifier
     * @return \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Feedback
     */
    public function retrieveFeedbackByIdentifier($feedbackIdentifier)
    {
        return DataManager::retrieve_by_id(Feedback::class_name(), $feedbackIdentifier);
    }

    /**
     *
     * @param \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry $entry
     * @return integer
     */
    public function countFeedbackByEntry(
        \Chamilo\Core\Repository\ContentObject\Assignment\Display\Storage\DataClass\Entry $entry)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID), 
            new StaticConditionVariable($entry->getId()));
        
        return DataManager::count(Feedback::class_name(), new DataClassCountParameters($condition));
    }

    /**
     *
     * @param \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry $entry
     * @return DataClassResultSet
     */
    public function findFeedbackByEntry(
        \Chamilo\Core\Repository\ContentObject\Assignment\Display\Storage\DataClass\Entry $entry)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Feedback::class_name(), Feedback::PROPERTY_ENTRY_ID), 
            new StaticConditionVariable($entry->getId()));
        
        return DataManager::retrieves(Feedback::class_name(), new DataClassRetrievesParameters($condition));
    }

    /**
     *
     * @param \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer[] $entityIdentifiers
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    public function findEntriesByPublicationEntityTypeAndIdentifiers(ContentObjectPublication $publication, $entityType, 
        $entityIdentifiers)
    {
        $conditions = array();
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publication->getId()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_TYPE), 
            new StaticConditionVariable($entityType));
        $conditions[] = new InCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_ENTITY_ID), 
            $entityIdentifiers);
        
        $condition = new AndCondition($conditions);
        
        return DataManager::retrieves(Entry::class_name(), new DataClassRetrievesParameters($condition));
    }

    /**
     *
     * @param \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication $publication
     * @return \Chamilo\Libraries\Storage\ResultSet\DataClassResultSet
     */
    public function findEntriesByPublication(ContentObjectPublication $publication)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Entry::class_name(), Entry::PROPERTY_PUBLICATION_ID), 
            new StaticConditionVariable($publication->getId()));
        
        return DataManager::retrieves(Entry::class_name(), new DataClassRetrievesParameters($condition));
    }
}