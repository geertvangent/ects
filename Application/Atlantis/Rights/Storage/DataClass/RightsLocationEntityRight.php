<?php
namespace Ehb\Application\Atlantis\Rights\Storage\DataClass;

use Ehb\Application\Atlantis\Rights\Storage\DataManager;

class RightsLocationEntityRight extends \Chamilo\Core\Rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
