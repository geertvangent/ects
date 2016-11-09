<?php
namespace Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex;

/**
 *
 * @author Hans De Bisschop
 */
class BamaflexDataConnector extends \Chamilo\Libraries\Storage\DataManager\DataManager
{

    public static $instance;

    public static function getInstance()
    {
        if (! isset(self :: $instance[static :: context()]))
        {
            $class = static :: context() . '\BamaflexDatabase';
            self :: $instance[static :: context()] = new $class();
        }
        return self :: $instance[static :: context()];
    }
}
