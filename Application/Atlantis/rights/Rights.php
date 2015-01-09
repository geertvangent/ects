<?php
namespace Chamilo\Application\Atlantis\Rights;

use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Storage\DataClassCountParameters;
use Chamilo\Libraries\Storage\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\InCondition;
use Chamilo\Application\Atlantis\Role\Entity\UserEntity;
use Chamilo\Application\Atlantis\Role\Entity\PlatformGroupEntity;
use Chamilo\Libraries\Storage\DataClassCache;
use Chamilo\Libraries\Storage\PropertyConditionVariable;
use Chamilo\Core\User\User;
use Chamilo\Core\Group\Group;
use Chamilo\Libraries\Storage\StaticConditionVariable;

class Rights extends \Chamilo\Core\Rights\RightsUtil
{
    const VIEW_RIGHT = '1';

    private static $instance;

    private static $target_users;

    private static $target_groups;

    private static $authorized_users;

    /**
     *
     * @return \repository\access\rights\Rights
     */
    public static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    public static function get_available_rights()
    {
        return array(Translation :: get('ViewRight') => self :: VIEW_RIGHT);
    }

    public function access_is_allowed()
    {
        $entities = array();
        $entities[UserEntity :: ENTITY_TYPE] = new UserEntity();
        $entities[PlatformGroupEntity :: ENTITY_TYPE] = new PlatformGroupEntity();
        
        return parent :: is_allowed(
            self :: VIEW_RIGHT, 
            __NAMESPACE__, 
            null, 
            $entities, 
            0, 
            self :: TYPE_ROOT, 
            0, 
            self :: TREE_TYPE_ROOT);
    }

    public function get_access_view_rights_location_entity_right($entity_id, $entity_type)
    {
        return parent :: get_rights_location_entity_right(
            __NAMESPACE__, 
            self :: VIEW_RIGHT, 
            $entity_id, 
            $entity_type, 
            self :: get_access_root_id());
    }

    public function invert_access_location_entity_right($right_id, $entity_id, $entity_type)
    {
        return parent :: invert_location_entity_right(
            __NAMESPACE__, 
            $right_id, 
            $entity_id, 
            $entity_type, 
            self :: get_access_root_id());
    }

    public function get_access_targets_entities()
    {
        return parent :: get_target_entities(self :: VIEW_RIGHT, __NAMESPACE__);
    }

    public function get_access_root()
    {
        return parent :: get_root(__NAMESPACE__);
    }

    public function get_access_root_id()
    {
        return parent :: get_root_id(__NAMESPACE__);
    }

    public function create_access_root()
    {
        return parent :: create_location(__NAMESPACE__);
    }

    public function get_access_location_entity_right($entity_id, $entity_type)
    {
        return \Chamilo\Core\Rights\DataManager :: retrieve_rights_location_entity_right(
            __NAMESPACE__, 
            self :: VIEW_RIGHT, 
            $entity_id, 
            $entity_type, 
            $this->get_access_root_id());
    }

    public function get_target_groups(User $user)
    {
        if (! isset(self :: $target_groups[$user->get_id()]))
        {
            $allowed_groups = array();
            
            $location_entity_right = $this->get_access_location_entity_right($user->get_id(), UserEntity :: ENTITY_TYPE);
            if ($location_entity_right instanceof RightsLocationEntityRight)
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        RightsLocationEntityRightGroup :: class_name(), 
                        RightsLocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID), 
                    new StaticConditionVariable($location_entity_right->get_id()));
                $right_groups = DataManager :: retrieves(RightsLocationEntityRightGroup :: class_name(), $condition);
                
                while ($right_group = $right_groups->next_result())
                {
                    if (! in_array($right_group->get_group_id(), $allowed_groups))
                    {
                        $allowed_groups[] = $right_group->get_group_id();
                    }
                }
            }
            
            $user_group_ids = $user->get_groups(true);
            
            foreach ($user_group_ids as $user_group_id)
            {
                $location_entity_right = $this->get_access_location_entity_right(
                    $user_group_id, 
                    PlatformGroupEntity :: ENTITY_TYPE);
                if ($location_entity_right instanceof RightsLocationEntityRight)
                {
                    $condition = new EqualityCondition(
                        new PropertyConditionVariable(
                            RightsLocationEntityRightGroup :: class_name(), 
                            RightsLocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID), 
                        new StaticConditionVariable($location_entity_right->get_id()));
                    $right_groups = DataManager :: retrieves(RightsLocationEntityRightGroup :: class_name(), $condition);
                    
                    while ($right_group = $right_groups->next_result())
                    {
                        if (! in_array($right_group->get_group_id(), $allowed_groups))
                        {
                            $allowed_groups[] = $right_group->get_group_id();
                        }
                    }
                }
            }
            
            self :: $target_groups[$user->get_id()] = $allowed_groups;
        }
        
        return self :: $target_groups[$user->get_id()];
    }

    public function get_target_users(User $user)
    {
        if (! isset(self :: $target_users[$user->get_id()]))
        {
            $allowed_groups = self :: get_target_groups($user);
            
            self :: $target_users[$user->get_id()] = array();
            
            if (count($allowed_groups) > 0)
            {
                DataClassCache :: truncate(Group :: class_name());
                $condition = new InCondition(
                    new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_ID), 
                    $allowed_groups);
                $groups = \Chamilo\Core\Group\DataManager :: retrieves(
                    Group :: class_name(), 
                    new DataClassRetrievesParameters($condition));
                
                while ($group = $groups->next_result())
                {
                    $user_ids = $group->get_users(true, true);
                    
                    foreach ($user_ids as $user_id)
                    {
                        if (! in_array($user_id, self :: $target_users[$user->get_id()]))
                        {
                            self :: $target_users[$user->get_id()][] = $user_id;
                        }
                    }
                }
            }
        }
        
        return self :: $target_users[$user->get_id()];
    }

    public function is_target_user(User $user, $target_user_id)
    {
        return in_array($target_user_id, $this->get_target_users($user));
    }

    public function is_target_group(User $user, $target_group_id)
    {
        foreach ($this->get_target_groups($user) as $group_id)
        {
            if ($target_group_id == $group_id)
            {
                return true;
            }
            else
            {
                $group = \Chamilo\Core\Group\DataManager :: retrieve_by_id(Group :: class_name(), (int) $group_id);
                if ($group->is_parent_of($target_group_id))
                {
                    return true;
                }
            }
        }
        
        return false;
    }

    public function get_authorized_users(User $user)
    {
        if (! isset(self :: $authorized_users[$user->get_id()]))
        {
            $location_entity_right_ids = array();
            $user_group_ids = $user->get_groups(true);
            
            foreach ($user_group_ids as $user_group_id)
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        RightsLocationEntityRightGroup :: class_name(), 
                        RightsLocationEntityRightGroup :: PROPERTY_GROUP_ID), 
                    new StaticConditionVariable($user_group_id));
                $right_groups = DataManager :: retrieves(RightsLocationEntityRightGroup :: class_name(), $condition);
                
                while ($right_group = $right_groups->next_result())
                {
                    if (! in_array($right_group->get_location_entity_right_id(), $location_entity_right_ids))
                    {
                        $location_entity_right_ids[] = $right_group->get_location_entity_right_id();
                    }
                }
            }
            
            $user_ids = array();
            
            if (count($location_entity_right_ids) > 0)
            {
                $condition = new InCondition(
                    new PropertyConditionVariable(
                        RightsLocationEntityRight :: class_name(), 
                        RightsLocationEntityRight :: PROPERTY_ID), 
                    $location_entity_right_ids);
                $location_entity_rights = \Chamilo\Core\Rights\DataManager :: get_instance()->retrieve_rights_location_rights(
                    __NAMESPACE__, 
                    $condition);
                
                while ($location_entity_right = $location_entity_rights->next_result())
                {
                    switch ($location_entity_right->get_entity_type())
                    {
                        case UserEntity :: ENTITY_TYPE :
                            if (! in_array($location_entity_right->get_entity_id(), $user_ids))
                            {
                                $user_ids[] = $location_entity_right->get_entity_id();
                            }
                            break;
                        case PlatformGroupEntity :: ENTITY_TYPE :
                            $group = \Chamilo\Core\Group\DataManager :: get_instance()->retrieve_group(
                                $location_entity_right->get_entity_id());
                            
                            if ($group instanceof Group)
                            {
                                $group_user_ids = $group->get_users(true, true);
                                
                                foreach ($group_user_ids as $group_user_id)
                                {
                                    if (! in_array($group_user_id, $user_ids))
                                    {
                                        $user_ids[] = $group_user_id;
                                    }
                                }
                            }
                            break;
                    }
                }
            }
            
            if (count($user_ids) > 0)
            {
                $condition = new InCondition(
                    new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID), 
                    $user_ids);
                $authorized_user_count = \Chamilo\Core\User\DataManager :: count(
                    User :: class_name(), 
                    new DataClassCountParameters($condition));
                
                if ($authorized_user_count == 0)
                {
                    $condition = new InCondition(
                        new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_PLATFORMADMIN), 
                        1);
                }
            }
            else
            {
                $condition = new InCondition(
                    new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_PLATFORMADMIN), 
                    1);
            }
            $authorized_users = \Chamilo\Core\User\DataManager :: retrieves(
                User :: class_name(), 
                new DataClassRetrievesParameters($condition));
            
            while ($authorized_user = $authorized_users->next_result())
            {
                self :: $authorized_users[$user->get_id()][] = $authorized_user;
            }
        }
        
        return self :: $authorized_users[$user->get_id()];
    }
}
