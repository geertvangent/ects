<?php
namespace application\atlantis\rights;

class RightsLocationEntityRight extends \core\rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
