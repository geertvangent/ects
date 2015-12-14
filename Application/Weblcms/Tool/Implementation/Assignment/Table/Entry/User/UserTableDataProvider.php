<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\User;

use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\User
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserTableDataProvider extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entry\EntryTableDataProvider
{

    /**
     *
     * @see \Chamilo\Libraries\Format\Table\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $orderProperty = null)
    {
        $assignmentDataProvider = $this->get_table()->getAssignmentDataProvider();
        $assignmentService = $assignmentDataProvider->getAssignmentService();

        var_dump($this->get_table()->getEntityId());
        exit;

        return $assignmentService->findPublicationEntriesForEntityTypeAndId(
            $assignmentDataProvider->getPublication(),
            Entry :: ENTITY_TYPE_USER,
            $this->get_table()->getEntityId(),
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
        return 1;
        // $assignmentDataProvider = $this->get_table()->getAssignmentDataProvider();
        // $assignmentService = $assignmentDataProvider->getAssignmentService();

        // return $assignmentService->countTargetUsersForPublication($assignmentDataProvider->getPublication(),
    // $condition);
    }
}