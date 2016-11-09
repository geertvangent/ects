<?php
namespace Ehb\Application\Discovery\Module\CourseResults;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager
{

    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    /**
     *
     * @param $module_instance Instance
     * @return DataManagerInterface
     */
    public static function getInstance($module_instance)
    {
        if (! isset(self :: $instance) || ! isset(self :: $instance[$module_instance->get_id()]))
        {
            $class = $module_instance->get_type() . '\\DataSource';
            self :: $instance[$module_instance->get_id()] = new $class($module_instance);
        }
        return self :: $instance[$module_instance->get_id()];
    }
}
