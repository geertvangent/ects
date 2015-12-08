<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service;

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
     * @param AssignmentService $assignmentService
     */
    public function __construct(AssignmentService $assignmentService, ContentObjectPublication $publication)
    {
        $this->assignmentService = $assignmentService;
        $this->publication = $publication;
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
        switch ($entityType)
        {
            case Entry :: ENTITY_TYPE_USER :
                $typeName = 'User';
                break;
            case Entry :: ENTITY_TYPE_COURSE_GROUP :
                $typeName = 'CourseGroup';
                break;
            case Entry :: ENTITY_TYPE_PLATFORM_GROUP :
                $typeName = 'Group';
                break;
        }

        $className = Manager :: package() . '\Table\Entity\\' . $typeName . '\\' . $typeName . 'Table';

        return new $className($application, $this);
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
}