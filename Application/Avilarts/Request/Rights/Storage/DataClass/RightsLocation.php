<?php
namespace Ehb\Application\Avilarts\Request\Rights\Storage\DataClass;

use Ehb\Application\Avilarts\Request\Rights\Storage\DataManager;

class RightsLocation extends \Chamilo\Core\Rights\RightsLocation
{

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
