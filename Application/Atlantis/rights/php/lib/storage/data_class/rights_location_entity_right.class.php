<?php
namespace application\atlantis\rights;

class RightsLocationEntityRight extends \rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
