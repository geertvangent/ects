<?php
namespace Application\EhbSync\cas;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\data_manager\DataManager
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
