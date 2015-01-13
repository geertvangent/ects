<?php
namespace Application\EhbApple;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\data_manager\DataManager
{
const PREFIX = 'ehb_apple_';
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
