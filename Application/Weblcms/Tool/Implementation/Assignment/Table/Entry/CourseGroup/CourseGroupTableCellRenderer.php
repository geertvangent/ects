<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\CourseGroup;

use Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTableCellRenderer;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\CourseGroup
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CourseGroupTableCellRenderer extends GroupTableCellRenderer
{

    /**
     *
     * @param integer $groupId
     * @return integer[]
     */
    protected function retrieveGroupUserIds($groupId)
    {
        return \Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataManager::retrieve_course_group_user_ids(
            $groupId);
    }

    /**
     *
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTableCellRenderer::getEntity()
     */
    protected function getEntity($entityId)
    {
        return DataManager::retrieve_by_id(CourseGroup::class_name(), $entityId);
    }

    /**
     *
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTableCellRenderer::isSubgroupMember()
     */
    protected function isSubgroupMember(DataClass $group, $userId)
    {
        foreach ($group->get_children() as $subgroup)
        {
            if ($this->isGroupMember($subgroup, $userId))
            {
                return true;
            }
        }
        
        return false;
    }

    /**
     *
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTableCellRenderer::isSubscribedInGroup()
     */
    protected function isSubscribedInGroup($groupId, $userId)
    {
        return \Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataManager::is_course_group_member(
            $groupId, 
            $userId);
    }
}