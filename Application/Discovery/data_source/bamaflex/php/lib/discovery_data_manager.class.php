<?php
namespace application\discovery\data_source\bamaflex;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager extends \common\libraries\DataManager
{

    public static function get_type()
    {
        return 'doctrine';
    }
}
