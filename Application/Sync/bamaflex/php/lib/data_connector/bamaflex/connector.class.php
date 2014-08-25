<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @author Hans De Bisschop
 */
class BamaflexDataConnector extends \libraries\DataManager
{

    public static $instance;

    public static function get_instance()
    {
        if (! isset(self :: $instance[static :: context()]))
        {
            $class = static :: context() . '\BamaflexDatabase';
            self :: $instance[static :: context()] = new $class();
        }
        return self :: $instance[static :: context()];
    }
}
