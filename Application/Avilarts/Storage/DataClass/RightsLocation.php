<?php
namespace Ehb\Application\Avilarts\Storage\DataClass;

use Ehb\Application\Avilarts\Storage\DataManager;

class RightsLocation extends \Chamilo\Core\Rights\RightsLocation
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
