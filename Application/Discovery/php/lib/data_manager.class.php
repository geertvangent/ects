<?php
namespace application\discovery;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager extends \common\libraries\DataManager
{

    /**
     * Gets the type of DataManager to be instantiated
     *
     * @return string
     */
    public static function get_type()
    {
        return 'doctrine';
    }
}
