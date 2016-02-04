<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\User;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\User
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserTableDataProvider extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableDataProvider
{

    /**
     *
     * @see \Chamilo\Libraries\Format\Table\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $orderProperty = null)
    {
        $assignmentDataProvider = $this->get_table()->getAssignmentDataProvider();
        $assignmentService = $assignmentDataProvider->getAssignmentService();

        return $assignmentService->findTargetUsersForPublication(
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

        return $assignmentService->countTargetUsersForPublication($assignmentDataProvider->getPublication(), $condition);
    }
}