<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service;

use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class AssignmentService
{

    /**
     *
     * @var \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository
     */
    private $assignmentRepository;

    /**
     *
     * @param AssignmentRepository $assignmentRepository
     */
    public function __construct(AssignmentRepository $assignmentRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
    }

    /**
     *
     * @return \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository
     */
    public function getAssignmentRepository()
    {
        return $this->assignmentRepository;
    }

    /**
     *
     * @param \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository $assignmentRepository
     */
    public function setAssignmentRepository(AssignmentRepository $assignmentRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
    }

    /**
     *
     * @param integer $publicationIdentifier
     * @return integer
     */
    public function countEntriesForPublicationIdentifier($publicationIdentifier)
    {
        return $this->getAssignmentRepository()->countEntriesForPublicationIdentifier($publicationIdentifier);
    }

    public function countDistinctEntriesByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        return $this->getAssignmentRepository()->countDistinctEntriesByPublicationAndEntityType(
            $publication,
            $entityType);
    }

    public function countDistinctFeedbackByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        return $this->getAssignmentRepository()->countDistinctFeedbackByPublicationAndEntityType(
            $publication,
            $entityType);
    }

    public function countDistinctLateEntriesByPublicationAndEntityType(ContentObjectPublication $publication,
        $entityType)
    {
        return $this->getAssignmentRepository()->countDistinctLateEntriesByPublicationAndEntityType(
            $publication,
            $entityType);
    }

    public function countEntitiesByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        switch ($entityType)
        {
            case Entry :: ENTITY_TYPE_USER :
                return $this->countTargetUsersForPublication($publication);
                break;

            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                return $this->countTargetCourseGroupsForPublication($publication);
                break;
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                return $this->countTargetGroupsForPublication($publication);
                break;
        }
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param \Chamilo\Libraries\Storage\Query\OrderBy[] $orderProperty
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findTargetUsersForPublication(ContentObjectPublication $publication, $condition, $offset, $count,
        $orderProperty)
    {
        return $this->getAssignmentRepository()->findTargetUsersForPublication(
            $publication,
            $condition,
            $offset,
            $count,
            $orderProperty);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return integer
     */
    public function countTargetUsersForPublication(ContentObjectPublication $publication, $condition = null)
    {
        return $this->getAssignmentRepository()->countTargetUsersForPublication($publication, $condition);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     */
    public function countFeedbackForPublicationByEntityTypeAndEntityId(ContentObjectPublication $publication,
        $entityType, $entityId)
    {
        return $this->getAssignmentRepository()->countFeedbackForPublicationByEntityTypeAndEntityId(
            $publication,
            $entityType,
            $entityId);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param \Chamilo\Libraries\Storage\Query\OrderBy[] $orderProperty
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findTargetCourseGroupsForPublication(ContentObjectPublication $publication, $condition, $offset,
        $count, $orderProperty)
    {
        return $this->getAssignmentRepository()->findTargetCourseGroupsForPublication(
            $publication,
            $condition,
            $offset,
            $count,
            $orderProperty);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return integer
     */
    public function countTargetCourseGroupsForPublication(ContentObjectPublication $publication, $condition = null)
    {
        return $this->getAssignmentRepository()->countTargetCourseGroupsForPublication($publication, $condition);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @param integer $offset
     * @param integer $count
     * @param \Chamilo\Libraries\Storage\Query\OrderBy[] $orderProperty
     * @return \Chamilo\Libraries\Storage\ResultSet\ArrayResultSet
     */
    public function findTargetGroupsForPublication(ContentObjectPublication $publication, $condition, $offset, $count,
        $orderProperty)
    {
        return $this->getAssignmentRepository()->findTargetGroupsForPublication(
            $publication,
            $condition,
            $offset,
            $count,
            $orderProperty);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param Condition $condition
     * @return integer
     */
    public function countTargetGroupsForPublication(ContentObjectPublication $publication, $condition = null)
    {
        return $this->getAssignmentRepository()->countTargetGroupsForPublication($publication, $condition);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @return integer
     */
    public function countEntriesForEntityTypeAndId(ContentObjectPublication $publication, $entityType, $entityId)
    {
        return $this->getAssignmentRepository()->countEntriesForEntityTypeAndId($publication, $entityType, $entityId);
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
        return $this->getAssignmentRepository()->countDistinctFeedbackForEntityTypeAndId(
            $publication,
            $entityType,
            $entityId);
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
        return $this->getAssignmentRepository()->countDistinctScoreForEntityTypeAndId(
            $publication,
            $entityType,
            $entityId);
    }

    /**
     *
     * @param ContentObjectPublication $publication
     * @param integer $entityType
     * @param integer $entityId
     * @return integer
     */
    public function getAverageScoreForEntityTypeAndId(ContentObjectPublication $publication, $entityType, $entityId)
    {
        return $this->getAssignmentRepository()->retrieveAverageScoreForEntityTypeAndId(
            $publication,
            $entityType,
            $entityId);
    }
}