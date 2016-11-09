<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\CourseGroup;

use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group\GroupTableDataProvider;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\CourseGroup
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CourseGroupTableDataProvider extends GroupTableDataProvider
{

    /**
     *
     * @see \Chamilo\Libraries\Format\Table\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $orderProperty = null)
    {
        $assignmentDataProvider = $this->get_table()->getAssignmentDataProvider();
        $assignmentService = $assignmentDataProvider->getAssignmentService();
        
        return $assignmentService->findTargetCourseGroupsForPublication(
            $assignmentDataProvider->getPublication(), 
            $condition, 
            $offset, 
            $count, 
            $orderProperty);
    }

    /**
     *
     * @see \Chamilo\Libraries\Format\Table\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        $assignmentDataProvider = $this->get_table()->getAssignmentDataProvider();
        $assignmentService = $assignmentDataProvider->getAssignmentService();
        
        return $assignmentService->countTargetCourseGroupsForPublication(
            $assignmentDataProvider->getPublication(), 
            $condition);
    }
}