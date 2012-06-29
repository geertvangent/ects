<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\CourseCategory;
use application\weblcms\WeblcmsDataManager;

use common\libraries\Utilities;

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
    
    static $official_code_cache;

    function __construct(CourseCategorySynchronization $synchronization, $parameters)
    {
        parent :: __construct();
        $this->synchronization = $synchronization;
        $this->parameters = $parameters;
        $this->determine_current_group();
    }

    function run()
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
    static function factory($type, CourseCategorySynchronization $synchronization, $parameters = array())
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'CourseCategorySynchronization';
        if (class_exists($class))
        {
            return new $class($synchronization, $parameters);
        }
    }

    function determine_current_group()
    {
        $this->current_group = WeblcmsDataManager :: get_instance()->retrieve_course_category_by_code($this->get_code());
    }

    /**
     *
     * @return boolean
     */
    function exists()
    {
        return $this->current_group instanceof CourseCategory;
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
    function set_current_group(CourseCategory $group)
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
    function get_children()
    {
        return array();
    }
}
?>