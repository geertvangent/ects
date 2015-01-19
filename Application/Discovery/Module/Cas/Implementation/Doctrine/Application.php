<?php
namespace Chamilo\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Chamilo\Application\Discovery\DiscoveryItem;

class Application extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }
}
