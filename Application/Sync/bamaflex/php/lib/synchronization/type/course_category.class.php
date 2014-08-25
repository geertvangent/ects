<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\CourseCategory;
use libraries\Utilities;

/**
 *
 * @package ehb.sync;
 */
class CourseCategorySynchronization extends Synchronization
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

    public function __construct(CourseCategorySynchronization $synchronization, $parameters)
    {
        parent :: __construct();
        $this->synchronization = $synchronization;
        $this->parameters = $parameters;
        $this->determine_current_group();
    }

    public function run()
    {
        $this->synchronize();
        $children = $this->get_children();

        foreach ($children as $child)
        {
            $child->run();
        }
    }

    /**
     *
     * @param $type string
     * @param $synchronization GroupSynchronization
     * @param $parameters array
     * @return GroupSynchronization
     */
    public static function factory($type, CourseCategorySynchronization $synchronization, $parameters = array())
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'CourseCategorySynchronization';
        if (class_exists($class))
        {
            return new $class($synchronization, $parameters);
        }
    }

    public function determine_current_group()
    {
        $this->current_group = \application\weblcms\DataManager :: retrieve_course_category_by_code(
            $this->get_code());
    }

    /**
     *
     * @return boolean
     */
    public function exists()
    {
        return $this->current_group instanceof CourseCategory;
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
    public function set_current_group(CourseCategory $group)
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

            $this->current_group = new CourseCategory();
            $this->current_group->set_name($name);
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
                $this->current_group->update();
            }
        }

        return $this->current_group;
    }

    /**
     *
     * @return array
     */
    public function get_children()
    {
        return array();
    }
}
