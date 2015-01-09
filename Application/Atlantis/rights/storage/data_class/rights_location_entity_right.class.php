<?php
namespace Chamilo\Application\Atlantis\rights\storage\data_class;

class RightsLocationEntityRight extends \core\rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
