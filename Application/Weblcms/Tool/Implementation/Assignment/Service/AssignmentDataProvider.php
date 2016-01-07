<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service;

use Chamilo\Application\Weblcms\Rights\WeblcmsRights;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;

/**
 *
 * @package Chamilo\Core\Repository\ContentObject\Assignment\Display\Preview
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class AssignmentDataProvider implements
    \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider
{

    /**
     *
     * @var AssignmentService
     */
    private $assignmentService;

    /**
     *
     * @var \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication
     */
    private $publication;

    /**
     *
     * @var \Chamilo\Libraries\Architecture\Application\Application
     */
    private $application;

    /**
     *
     * @param AssignmentService $assignmentService
     * @param \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication $publication
     * @param \Chamilo\Libraries\Architecture\Application\Application $application
     */
    public function __construct(AssignmentService $assignmentService, ContentObjectPublication $publication,
        Application $application)
    {
        $this->assignmentService = $assignmentService;
        $this->publication = $publication;
        $this->application = $application;
    }

    /**
     *
     * @return \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service\AssignmentService
     */
    public function getAssignmentService()
    {
        return $this->assignmentService;
    }

    /**
     *
     * @param \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service\AssignmentService $assignmentService
     */
    public function setAssignmentService(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     *
     * @return \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     *
     * @param \Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication $publication
     */
    public function setPublication(ContentObjectPublication $publication)
    {
        $this->publication = $publication;
    }

    /**
     *
     * @return \Chamilo\Libraries\Architecture\Application\Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     *
     * @param \Chamilo\Libraries\Architecture\Application\Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countDistinctEntriesByEntityType()
     */
    public function countDistinctEntriesByEntityType($entityType)
    {
        return $this->getAssignmentService()->countDistinctEntriesByPublicationAndEntityType(
            $this->getPublication(),
            $entityType);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countDistinctFeedbackByEntityType()
     */
    public function countDistinctFeedbackByEntityType($entityType)
    {
        return $this->getAssignmentService()->countDistinctFeedbackByPublicationAndEntityType(
            $this->getPublication(),
            $entityType);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countDistinctLateEntriesByEntityType()
     */
    public function countDistinctLateEntriesByEntityType($entityType)
    {
        return $this->getAssignmentService()->countDistinctLateEntriesByPublicationAndEntityType(
            $this->getPublication(),
            $entityType);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countEntitiesByEntityType()
     */
    public function countEntitiesByEntityType($entityType)
    {
        return $this->getAssignmentService()->countEntitiesByPublicationAndEntityType(
            $this->getPublication(),
            $entityType);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::getEntityNameByType()
     */
    public function getEntityNameByType($entityType)
    {
        switch ($entityType)
        {
            case Entry :: ENTITY_TYPE_USER :
                return Translation :: get('Users');
                break;
            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                return Translation :: get('CourseGroups');
                break;
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                return Translation :: get('PlatformGroups');
                break;
        }
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::getEntityTableForType()
     */
    public function getEntityTableForType(Application $application, $entityType)
    {
        $tableName = $this->getTableNameForEntityType('Entity', $application, $entityType);
        return new $tableName($application, $this);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::getEntryTableForEntityTypeAndId()
     */
    public function getEntryTableForEntityTypeAndId(Application $application, $entityType, $entityId)
    {
        $tableName = $this->getTableNameForEntityType('Entry', $application, $entityType);
        return new $tableName($application, $this, $entityId);
    }

    /**
     *
     * @param string $tableType
     * @param \Chamilo\Libraries\Architecture\Application\Application $application
     * @param integer $entityType
     * @return \Chamilo\Libraries\Format\Table\Table
     */
    protected function getTableNameForEntityType($tableType, Application $application, $entityType)
    {
        $typeName = $this->getTypeNameForEntityType($entityType);
        return Manager :: package() . '\Table\\' . $tableType . '\\' . $typeName . '\\' . $typeName . 'Table';
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::getCurrentEntityType()
     */
    public function getCurrentEntityType()
    {
        $contentObject = $this->getPublication()->get_content_object();

        if ($contentObject->get_allow_group_submissions())
        {
            return Entry :: ENTITY_TYPE_COURSE_GROUP;
        }
        else
        {
            return Entry :: ENTITY_TYPE_USER;
        }
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::isDateAfterAssignmentEndTime()
     */
    public function isDateAfterAssignmentEndTime($date)
    {
        $assignment = $this->getPublication()->get_content_object();
        return $date > $assignment->get_end_time();
    }

    public function countFeedbackByEntityTypeAndEntityId($entityType, $entityId)
    {
        return $this->getAssignmentService()->countFeedbackForPublicationByEntityTypeAndEntityId(
            $this->getPublication(),
            $entityType,
            $entityId);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::canEditAssignment()
     */
    public function canEditAssignment()
    {
        return $this->getApplication()->is_allowed(WeblcmsRights :: EDIT_RIGHT);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::createEntry()
     */
    public function createEntry($entityType, $entityId, $userId, $contentObjectId, $ipAddress)
    {
        $entry = new Entry();
        $entry->setPublicationId($this->getPublication()->getId());
        $entry->setContentObjectId($contentObjectId);
        $entry->setSubmitted(time());
        $entry->setEntityId($entityId);
        $entry->setEntityType($entityType);
        $entry->setUserId($userId);
        $entry->setIpAddress($ipAddress);

        if (! $entry->create())
        {
            return false;
        }

        return $entry;
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countEntriesForEntityTypeAndId()
     */
    public function countEntriesForEntityTypeAndId($entityType, $entityId)
    {
        return $this->getAssignmentService()->countEntriesForPublicationEntityTypeAndId(
            $this->getPublication(),
            $entityType,
            $entityId);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countDistinctFeedbackForEntityTypeAndId()
     */
    public function countDistinctFeedbackForEntityTypeAndId($entityType, $entityId)
    {
        return $this->getAssignmentService()->countDistinctFeedbackForEntityTypeAndId(
            $this->getPublication(),
            $entityType,
            $entityId);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countDistinctScoreForEntityTypeAndId()
     */
    public function countDistinctScoreForEntityTypeAndId($entityType, $entityId)
    {
        return $this->getAssignmentService()->countDistinctScoreForEntityTypeAndId(
            $this->getPublication(),
            $entityType,
            $entityId);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::getAverageScoreForEntityTypeAndId()
     */
    public function getAverageScoreForEntityTypeAndId($entityType, $entityId)
    {
        return $this->getAssignmentService()->getAverageScoreForEntityTypeAndId(
            $this->getPublication(),
            $entityType,
            $entityId);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::countFeedbackByEntryIdentifier()
     */
    public function countFeedbackByEntryIdentifier($entryIdentifier)
    {
        return $this->getAssignmentService()->countFeedbackByEntryIdentifier($entryIdentifier);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::findEntryByIdentifier()
     */
    public function findEntryByIdentifier($entryIdentifier)
    {
        return $this->getAssignmentService()->findEntryByIdentifier($entryIdentifier);
    }

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Interfaces\AssignmentDataProvider::getEntityRendererForEntityTypeAndId()
     */
    public function getEntityRendererForEntityTypeAndId(Application $application, $entityType, $entityId)
    {
        $rendererName = $this->getEntityRendererNameForEntityType($application, $entityType);
        return new $rendererName($application, $this, $entityId);
    }

    /**
     *
     * @param \Chamilo\Libraries\Architecture\Application\Application $application
     * @param integer $entityType
     * @return \Chamilo\Libraries\Format\Table\Table
     */
    protected function getEntityRendererNameForEntityType(Application $application, $entityType)
    {
        $typeName = $this->getTypeNameForEntityType($entityType);
        return Manager :: package() . '\Renderer\Entity\\' . $typeName . 'EntityRenderer';
    }

    /**
     *
     * @param integer $entityType
     * @return string
     */
    protected function getTypeNameForEntityType($entityType)
    {
        switch ($entityType)
        {
            case Entry :: ENTITY_TYPE_USER :
                return 'User';
                break;
            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                return 'CourseGroup';
                break;
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                return 'PlatformGroup';
                break;
        }
    }
}