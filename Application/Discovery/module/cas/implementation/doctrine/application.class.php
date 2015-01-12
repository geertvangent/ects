<?php
namespace Application\Discovery\module\cas\implementation\doctrine;

use application\discovery\DiscoveryItem;

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
