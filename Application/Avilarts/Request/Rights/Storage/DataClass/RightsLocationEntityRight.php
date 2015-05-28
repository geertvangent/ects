<?php
namespace Ehb\Application\Avilarts\Request\Rights\Storage\DataClass;

use Ehb\Application\Avilarts\Request\Rights\Storage\DataManager;

class RightsLocationEntityRight extends \Chamilo\Core\Rights\RightsLocationEntityRight
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
?>