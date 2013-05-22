<?php
namespace application\atlantis\rights;

use rights\RightsLocationEntityRight;
use rights\NewPlatformGroupEntity;
use rights\NewUserEntity;
use rights\RightsUtil;
use rights\RightsDataManager;
use common\libraries\Translation;
use common\libraries\EqualityCondition;
use common\libraries\DataClassCountParameters;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\InCondition;

class Rights extends RightsUtil
{
    const VIEW_RIGHT = '1';

    private static $instance;

    private static $target_users;

    private static $authorized_users;

    /**
     *
     * @return \repository\quota\rights\Rights
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
        $entities[NewUserEntity :: ENTITY_TYPE] = new NewUserEntity();
        $entities[NewPlatformGroupEntity :: ENTITY_TYPE] = new NewPlatformGroupEntity();

        return parent :: is_allowed(
            self :: VIEW_RIGHT,
            'atlantis',
            null,
            $entities,
            0,
            self :: TYPE_ROOT,
            0,
            self :: TREE_TYPE_ROOT);
    }

    public function get_quota_view_rights_location_entity_right($entity_id, $entity_type)
    {
        return parent :: get_rights_location_entity_right(
            'atlantis',
            self :: VIEW_RIGHT,
            $entity_id,
            $entity_type,
            self :: get_quota_root_id());
    }

    public function invert_quota_location_entity_right($right_id, $entity_id, $entity_type)
    {
        return parent :: invert_location_entity_right(
            'atlantis',
            $right_id,
            $entity_id,
            $entity_type,
            self :: get_quota_root_id());
    }

    public function get_quota_targets_entities()
    {
        return parent :: get_target_entities(self :: VIEW_RIGHT, 'atlantis');
    }

    public function get_quota_root()
    {
        return parent :: get_root('atlantis');
    }

    public function get_quota_root_id()
    {
        return parent :: get_root_id('atlantis');
    }

    public function create_quota_root()
    {
        return parent :: create_location('atlantis');
    }

    public function get_quota_location_entity_right($entity_id, $entity_type)
    {
        return RightsDataManager :: get_instance()->retrieve_rights_location_entity_right(
            'atlantis',
            self :: VIEW_RIGHT,
            $entity_id,
            $entity_type,
            $this->get_quota_root_id());
    }

    public function get_target_users(\user\User $user)
    {
        if (! isset(self :: $target_users[$user->get_id()]))
        {
            $allowed_groups = array();

            $location_entity_right = $this->get_quota_location_entity_right(
                $user->get_id(),
                NewUserEntity :: ENTITY_TYPE);
            if ($location_entity_right instanceof RightsLocationEntityRight)
            {
                $condition = new EqualityCondition(
                    LocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID,
                    $location_entity_right->get_id());
                $right_groups = DataManager :: retrieves(LocationEntityRightGroup :: class_name(), $condition);

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
                $location_entity_right = $this->get_quota_location_entity_right(
                    $user_group_id,
                    NewPlatformGroupEntity :: ENTITY_TYPE);
                if ($location_entity_right instanceof RightsLocationEntityRight)
                {
                    $condition = new EqualityCondition(
                        LocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID,
                        $location_entity_right->get_id());
                    $right_groups = DataManager :: retrieves(LocationEntityRightGroup :: class_name(), $condition);

                    while ($right_group = $right_groups->next_result())
                    {
                        if (! in_array($right_group->get_group_id(), $allowed_groups))
                        {
                            $allowed_groups[] = $right_group->get_group_id();
                        }
                    }
                }
            }

            self :: $target_users[$user->get_id()] = array();

            if (count($allowed_groups) > 0)
            {
                $condition = new InCondition(\group\Group :: PROPERTY_ID, $allowed_groups);
                $groups = \group\DataManager :: get_instance()->retrieve_groups($condition);

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

    public function is_target_user(\user\User $user, $target_user_id)
    {
        return in_array($target_user_id, $this->get_target_users($user));
    }

    public function get_authorized_users(\user\User $user)
    {
        if (! isset(self :: $authorized_users[$user->get_id()]))
        {
            $location_entity_right_ids = array();
            $user_group_ids = $user->get_groups(true);

            foreach ($user_group_ids as $user_group_id)
            {
                $condition = new EqualityCondition(LocationEntityRightGroup :: PROPERTY_GROUP_ID, $user_group_id);
                $right_groups = DataManager :: retrieves(LocationEntityRightGroup :: class_name(), $condition);

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
                $condition = new InCondition(LocationEntityRight :: PROPERTY_ID, $location_entity_right_ids);
                $location_entity_rights = RightsDataManager :: get_instance()->retrieve_rights_location_rights(
                    'atlantis',
                    $condition);

                while ($location_entity_right = $location_entity_rights->next_result())
                {
                    switch ($location_entity_right->get_entity_type())
                    {
                        case NewUserEntity :: ENTITY_TYPE :
                            if (! in_array($location_entity_right->get_entity_id(), $user_ids))
                            {
                                $user_ids[] = $location_entity_right->get_entity_id();
                            }
                            break;
                        case NewPlatformGroupEntity :: ENTITY_TYPE :
                            $group = \group\DataManager :: get_instance()->retrieve_group(
                                $location_entity_right->get_entity_id());

                            if ($group instanceof \group\Group)
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
                $condition = new InCondition(\user\User :: PROPERTY_ID, $user_ids);
                $authorized_user_count = \user\DataManager :: count(
                    \user\User :: class_name(),
                    new DataClassCountParameters($condition));

                if ($authorized_user_count == 0)
                {
                    $condition = new InCondition(\user\User :: PROPERTY_PLATFORMADMIN, 1);
                }
            }
            else
            {
                $condition = new InCondition(\user\User :: PROPERTY_PLATFORMADMIN, 1);
            }
            $authorized_users = \user\DataManager :: retrieves(
                \user\User :: class_name(),
                new DataClassRetrievesParameters($condition));

            while ($authorized_user = $authorized_users->next_result())
            {
                self :: $authorized_users[$user->get_id()][] = $authorized_user;
            }
        }

        return self :: $authorized_users[$user->get_id()];
    }
}
