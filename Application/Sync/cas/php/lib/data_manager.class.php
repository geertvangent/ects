<?php
namespace application\ehb_sync\cas;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \common\libraries\DataManager
{

    /**
     * Gets the type of DataManager to be instantiated
     *
     * @return string
     */
    static function get_type()
    {
        return 'doctrine';
    }
}
