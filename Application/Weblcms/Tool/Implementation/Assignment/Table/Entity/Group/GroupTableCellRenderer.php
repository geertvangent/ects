<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Storage\DataClass\Entry;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class GroupTableCellRenderer extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableCellRenderer
{

    public function render_cell($column, $entity)
    {
        if ($column->get_name() == GroupTableColumnModel :: PROPERTY_GROUP_MEMBERS)
        {
            return $this->getGroupMembers($entity);
        }

        return parent :: render_cell($column, $entity);
    }

    /**
     *
     * @param Chamilo\Libraries\Storage\DataClass\DataClass $group
     * @return string
     */
    protected function getGroupMembers($group)
    {
        $entityId = $group[Entry :: PROPERTY_ENTITY_ID];

        $limit = 21;

        $orderProperties = array();
        $orderProperties[] = new OrderBy(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_LASTNAME));
        $orderProperties[] = new OrderBy(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_FIRSTNAME));

        $userIds = $this->retrieveGroupUserIds($entityId);

        $condition = new InCondition(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID), $userIds);

        $users = \Chamilo\Core\User\Storage\DataManager :: retrieves(
            User :: class_name(),
            new DataClassRetrievesParameters($condition, $limit, null, $orderProperties))->as_array();

        if (count($users) == 0)
        {
            return null;
        }

        $exceedsLimit = false;

        if (count($users) == $limit)
        {
            $exceedsLimit = true;
            array_pop($users);
        }

        $html = array();
        $html[] = '<select style="width:180px">';

        foreach ($users as $user)
        {
            $html[] = '<option>' . $user->get_fullname() . '</option>';
        }

        if ($exceedsLimit)
        {
            $html[] = '<option>...</option>';
        }

        $html[] = '</select>';

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param integer $groupId
     * @return integer[]
     */
    abstract protected function retrieveGroupUserIds($groupId);

    /**
     *
     * @see \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTableCellRenderer::isEntity()
     */
    protected function isEntity($entityId, $userId)
    {
        return $this->isGroupMember($this->getEntity($entityId), $userId);
    }

    /**
     *
     * @param integer $entityId
     * @return \Chamilo\Libraries\Storage\DataClass\DataClass
     */
    abstract protected function getEntity($entityId);

    /**
     *
     * @param DataClass $entity
     * @param integer $userId
     * @return boolean
     */
    protected function isGroupMember(DataClass $entity, $userId)
    {
        if ($this->isSubscribedInGroup($entity->getId(), $userId))
        {
            return true;
        }

        if ($entity->has_children())
        {
            return $this->isSubgroupMember($entity, $userId);
        }

        return false;
    }

    /**
     *
     * @param DataClass $entity
     * @param integer $userId
     * @return boolean
     */
    abstract protected function isSubgroupMember(DataClass $entity, $userId);

    /**
     *
     * @param integer $groupId
     * @param integer $userId
     * @return boolean
     */
    abstract protected function isSubscribedInGroup($groupId, $userId);
}