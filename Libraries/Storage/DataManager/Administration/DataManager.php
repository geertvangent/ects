<?php
namespace Ehb\Libraries\Storage\DataManager\Administration;

/**
 *
 * @package Ehb\Libraries\Storage\DataManager\Administration
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{

    /**
     *
     * @var \Ehb\Libraries\Storage\DataManager\Administration\Database
     */
    public static $instance;

    /**
     *
     * @return \Ehb\Libraries\Storage\DataManager\Administration\Database
     */
    public static function get_instance()
    {
        if (! isset(self::$instance[static::context()]))
        {
            $class = __NAMESPACE__ . '\Database';
            self::$instance[static::context()] = new $class();
        }

        return self::$instance[static::context()];
    }
}
