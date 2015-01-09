<?php
namespace Chamilo\Application\Atlantis\rights\storage;

class DataManager extends \libraries\storage\data_manager\DataManager
{
    const PREFIX = 'atlantis_';

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
