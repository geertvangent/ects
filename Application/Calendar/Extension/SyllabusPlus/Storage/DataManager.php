<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataBase
     */
    public static $instance;

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataBase
     */
    public static function get_instance()
    {
        if (! isset(self :: $instance[static :: context()]))
        {
            $class = static :: context() . '\Database';
            self :: $instance[static :: context()] = new $class();
        }
        return self :: $instance[static :: context()];
    }
}
