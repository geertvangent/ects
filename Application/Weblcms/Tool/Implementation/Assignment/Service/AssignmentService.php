<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Service;

use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Repository\AssignmentRepository;
use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;

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
        return 1;
    }

    public function countDistinctFeedbackByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        return 0;
    }

    public function countDistinctLateEntriesByPublicationAndEntityType(ContentObjectPublication $publication,
        $entityType)
    {
        return 0;
    }

    public function countEntitiesByPublicationAndEntityType(ContentObjectPublication $publication, $entityType)
    {
        return 10;
    }
}