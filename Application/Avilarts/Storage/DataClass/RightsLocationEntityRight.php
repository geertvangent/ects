<?php
namespace Ehb\Application\Avilarts\Storage\DataClass;

use Ehb\Application\Avilarts\Storage\DataManager;

class RightsLocationEntityRight extends \Chamilo\Core\Rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}