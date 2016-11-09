<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\PlatformGroup;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\DataManager\DataManager;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group\GroupTableCellRenderer;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\PlatformGroup
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PlatformGroupTableCellRenderer extends GroupTableCellRenderer
{

    /**
     *
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group\GroupTableCellRenderer::retrieveGroupUserIds()
     */
    protected function retrieveGroupUserIds($groupId)
    {
        return DataManager::retrieve_by_id(Group::class_name(), $groupId)->get_users(true, true);
    }

    /**
     *
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group\GroupTableCellRenderer::getEntity()
     */
    protected function getEntity($entityId)
    {
        return DataManager::retrieve_by_id(Group::class_name(), $entityId);
    }

    /**
     *
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group\GroupTableCellRenderer::isSubgroupMember()
     */
    protected function isSubgroupMember(DataClass $entity, $userId)
    {
        foreach ($entity->get_subgroups() as $subgroup)
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
     * @see \Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group\GroupTableCellRenderer::isSubscribedInGroup()
     */
    protected function isSubscribedInGroup($groupId, $userId)
    {
        return \Chamilo\Core\Group\Storage\DataManager::is_group_member($groupId, $userId);
    }
}