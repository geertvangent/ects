<?php
namespace application\ehb_sync\bamaflex;

use user\User;

use common\libraries\InCondition;

use user\UserDataManager;

use group\GroupRelUser;
use group\GroupDataManager;
use group\Group;

use common\libraries\EqualityCondition;
use common\libraries\Filesystem;
use common\libraries\Utilities;
use common\libraries\AndCondition;

/**
 *
 * @package ehb.sync;
 */

class ArchiveGroupSynchronization extends Synchronization
{
    /**
     *
     * @var Synchronization
     */
    private $synchronization;

    /**
     *
     * @var array
     */
    private $parameters;

    /**
     *
     * @var Group
     */
    private $parent_group;

    /**
     *
     * @var Group
     */
    private $current_group;

    static $official_code_cache;

    function __construct(ArchiveGroupSynchronization $synchronization, $parameters)
    {
        parent :: __construct();
        $this->synchronization = $synchronization;
        $this->parameters = $parameters;
        $this->determine_current_group();
    }

    function run()
    {
        $this->synchronize();
        $this->synchronize_users();
        $children = $this->get_children();

        foreach ($children as $child)
        {
            $child->run();
        }
    }

    /**
     * Enter description here .
     *
     *
     *
     * ..
     *
     * @param $type string
     * @param $synchronization GroupSynchronization
     * @param $parameters array
     * @return GroupSynchronization
     */
    static function factory($type, ArchiveGroupSynchronization $synchronization, $parameters = array())
    {
        $file = dirname(__FILE__) . '/archive_group/' . $type . '.class.php';
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'GroupSynchronization';
        if (file_exists($file))
        {
            require_once $file;
            return new $class($synchronization, $parameters);
        }
    }

    function determine_current_group()
    {
        $this->current_group = GroupDataManager :: get_instance()->retrieve_group_by_code_and_parent_id($this->get_code(), $this->get_parent_group()->get_id());
    }

    /**
     *
     * @return boolean
     */
    function exists()
    {
        return $this->current_group instanceof Group;
    }

    /**
     *
     * @return Group
     */
    function get_current_group()
    {
        return $this->current_group;
    }

    /**
     *
     * @param $group Group
     */
    function set_current_group(Group $group)
    {
        $this->current_group = $group;
    }

    /**
     *
     * @return Synchronization
     */
    function get_synchronization()
    {
        return $this->synchronization;
    }

    /**
     *
     * @return Group
     */
    function get_parent_group()
    {
        return $this->get_synchronization()->get_current_group();
    }

    /**
     *
     * @return array
     */
    function get_parameters()
    {
        return $this->parameters;
    }

    /**
     *
     * @param $key string
     * @return string
     */
    function get_parameter($key)
    {
        return $this->parameters[$key];
    }

    /**
     *
     * @return Group
     */
    function synchronize()
    {
        if (! $this->exists())
        {
            $name = $this->convert_to_utf8($this->get_name());

            $this->current_group = new Group();
            $this->current_group->set_name($name);
            $this->current_group->set_description($name);
            $this->current_group->set_code($this->get_code());
            $this->current_group->set_parent($this->get_parent_group()->get_id());
            $this->current_group->create();

            self :: log('added', $this->current_group->get_name());
            flush();
        }
        else
        {
            $name = $this->convert_to_utf8($this->get_name());
            if ($this->current_group->get_name() != $name)
            {
                $this->current_group->set_name($name);
                $this->current_group->set_description($name);
                $this->current_group->update();
            }
        }

        return $this->current_group;
    }

    function synchronize_users()
    {
        $group_data_manager = GroupDataManager :: get_instance();

        $condition = new EqualityCondition(GroupRelUser :: PROPERTY_GROUP_ID, $this->current_group->get_id());
        $current_users = $group_data_manager->retrieve_distinct(GroupRelUser :: get_table_name(), GroupRelUser :: PROPERTY_USER_ID, $condition);
        $source_users = $this->get_users();
        //         $source_users = array();
        $to_add = array_diff($source_users, $current_users);
        $to_delete = array_diff($current_users, $source_users);

        foreach ($to_add as $user_id)
        {
            $relation = new GroupRelUser();
            $relation->set_group_id($this->current_group->get_id());
            $relation->set_user_id($user_id);
            $relation->create();
        }

        $conditions = array();
        $conditions[] = new EqualityCondition(GroupRelUser :: PROPERTY_GROUP_ID, $this->current_group->get_id());
        $conditions[] = new InCondition(GroupRelUser :: PROPERTY_USER_ID, $to_delete);
        $condition = new AndCondition($conditions);

        return $group_data_manager->delete(GroupRelUser :: get_table_name(), $condition);
    }

    /**
     *
     * @return array
     */
    function get_children()
    {
        return array();
    }

    function get_user_official_codes()
    {
        return array();
    }

    function get_users()
    {
        $official_codes = $this->get_user_official_codes();
        $user_ids = array();
        if (count($official_codes) > 0)
        {
            foreach ($official_codes as $code)
            {
                if (! isset(self :: $official_code_cache[$code]))
                {
                    $result_codes[] = $code;
                }
                else
                {
                    $user_ids[] = self :: $official_code_cache[$code];
                }
            }

            if (count($result_codes) > 0)
            {
                $results = \user\DataManager :: retrieve_users_by_official_codes($result_codes);
                while ($result = $results->next_result())
                {
                    $user_ids[] = $result->get_id();
                    self :: $official_code_cache[$result->get_official_code()] = $result->get_id();
                }
            }
        }

        return $user_ids;
    }

    function get_academic_year()
    {
        return '2011-12';
    }

    function get_academic_year_end()
    {
        $year_parts = explode('-', $this->get_academic_year());

        return '20'. $year_parts[1] .'-09-30 23:59:59.999';
    }
}
?>