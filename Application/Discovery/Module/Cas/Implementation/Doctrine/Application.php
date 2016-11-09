<?php
namespace Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Ehb\Application\Discovery\DiscoveryItem;

class Application extends DiscoveryItem
{

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }
}
