<?php
namespace application\atlantis\user_group;

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
