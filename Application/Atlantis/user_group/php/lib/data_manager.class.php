<?php
namespace application\atlantis\context;

class DataManager extends \common\libraries\DataManager
{

    /**
     * Gets the type of DataManager to be instantiated
     *
     * @return string
     */
    static function get_type()
    {
        return 'mdb2';
    }
}
?>