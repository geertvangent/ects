<?php
namespace Chamilo\Application\Atlantis\Rights\Storage\DataClass;

use Chamilo\Application\Atlantis\Rights\Storage\DataManager;

class RightsLocationEntityRight extends \Chamilo\Core\Rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
