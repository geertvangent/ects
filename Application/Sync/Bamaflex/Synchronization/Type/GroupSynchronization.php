<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\Group\Storage\DataClass\GroupRelUser;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;

/**
 *
 * @package ehb.sync;
 */
class GroupSynchronization extends Synchronization
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

    public static $official_code_cache;

    /**
     *
     * @var integer
     */
    private $userCount;

    public function __construct(GroupSynchronization $synchronization, $parameters)
    {
        parent::__construct();
        $this->synchronization = $synchronization;
        $this->parameters = $parameters;
        $this->determine_current_group();
    }

    public function run()
    {
        $this->synchronize();
        $this->synchronize_users();
        $children = $this->get_children();

        // $anyChildActive = false;

        foreach ($children as $child)
        {
            $child->run();

            // if ($child->get_current_group()->get_state() == 1)
            // {
            // $anyChildActive = true;
            // }
        }

        // if ((count($children) == 0 && $this->userCount == 0) || (! $anyChildActive && $this->userCount == 0))
        // {
        // $this->get_current_group()->set_state(0);
        // $this->get_current_group()->update();
        // }
        // elseif ($this->get_current_group()->get_state() == 0)
        // {
        // $this->get_current_group()->set_state(1);
        // $this->get_current_group()->update();
        // }
    }

    /**
     *
     * @param $type string
     * @param $synchronization GroupSynchronization
     * @param $parameters array
     * @return GroupSynchronization
     */
    public static function factory($type, GroupSynchronization $synchronization, $parameters = array())
    {
        if (class_exists($type))
        {
            $class = $type;
        }
        else
        {
            $class = __NAMESPACE__ . '\Group\\' . StringUtilities::getInstance()->createString($type)->upperCamelize() .
                 'GroupSynchronization';
        }

        return new $class($synchronization, $parameters);
    }

    public function determine_current_group()
    {
        $this->current_group = \Chamilo\Core\Group\Storage\DataManager::retrieve_group_by_code_and_parent_id(
            $this->get_code(),
            $this->get_parent_group()->get_id());
    }

    /**
     *
     * @return boolean
     */
    public function exists()
    {
        return $this->current_group instanceof Group;
    }

    /**
     *
     * @return Group
     */
    public function get_current_group()
    {
        return $this->current_group;
    }

    /**
     *
     * @param $group Group
     */
    public function set_current_group(Group $group)
    {
        $this->current_group = $group;
    }

    /**
     *
     * @return Synchronization
     */
    public function get_synchronization()
    {
        return $this->synchronization;
    }

    /**
     *
     * @return Group
     */
    public function get_parent_group()
    {
        return $this->get_synchronization()->get_current_group();
    }

    /**
     *
     * @return array
     */
    public function get_parameters()
    {
        return $this->parameters;
    }

    /**
     *
     * @param $key string
     * @return string
     */
    public function get_parameter($key)
    {
        return $this->parameters[$key];
    }

    /**
     *
     * @return Group
     */
    public function synchronize()
    {
        if (! $this->exists())
        {
            $name = $this->convert_to_utf8($this->get_name());

            $this->current_group = new Group();
            $this->current_group->set_name($name);
            $this->current_group->set_description($name);
            $this->current_group->set_code($this->get_code());
            $this->current_group->set_parent($this->get_parent_group()->get_id());
            $this->current_group->set_sort(0);
            $this->current_group->create();

            self::log('added', $this->current_group->get_name());
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

    public function synchronize_users()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(GroupRelUser::class_name(), GroupRelUser::PROPERTY_GROUP_ID),
            new StaticConditionVariable($this->current_group->get_id()));
        $current_users = \Chamilo\Core\Group\Storage\DataManager::distinct(
            GroupRelUser::class_name(),
            new DataClassDistinctParameters($condition, GroupRelUser::PROPERTY_USER_ID));
        $source_users = $this->get_users();
        // $source_users = array();
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
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(GroupRelUser::class_name(), GroupRelUser::PROPERTY_GROUP_ID),
            new StaticConditionVariable($this->current_group->get_id()));
        $conditions[] = new InCondition(
            new PropertyConditionVariable(GroupRelUser::class_name(), GroupRelUser::PROPERTY_USER_ID),
            $to_delete);

        $condition = new AndCondition($conditions);

        \Chamilo\Core\Group\Storage\DataManager::deletes(GroupRelUser::class_name(), $condition);
    }

    /**
     *
     * @return array
     */
    public function get_children()
    {
        return array();
    }

    public function get_user_official_codes()
    {
        return array();
    }

    public function get_users()
    {
        $official_codes = $this->get_user_official_codes();

        $userIds = array();

        if (count($official_codes) > 0)
        {
            foreach ($official_codes as $code)
            {
                if (! isset(self::$official_code_cache[$code]))
                {
                    $result_codes[] = $code;
                }
                else
                {
                    $userIds[] = self::$official_code_cache[$code];
                }
            }

            if (count($result_codes) > 0)
            {
                $results = \Chamilo\Core\User\Storage\DataManager::retrieve_users_by_official_codes($result_codes);
                while ($result = $results->next_result())
                {
                    $userIds[] = $result->get_id();
                    self::$official_code_cache[$result->get_official_code()] = $result->get_id();
                }
            }
        }

        $this->userCount = count($userIds);

        return $userIds;
    }
}
