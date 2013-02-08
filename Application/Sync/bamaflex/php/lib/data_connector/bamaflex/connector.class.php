<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @author Hans De Bisschop
 */
class BamaflexDataConnector extends \common\libraries\DataManager
{
    static $instance;

    static function get_instance()
    {
        if (! isset(self :: $instance[static :: context()]))
        {
            $class = static :: context() . '\BamaflexDatabase';
            self :: $instance[static :: context()] = new $class();
        }
        return self :: $instance[static :: context()];
    }

}
