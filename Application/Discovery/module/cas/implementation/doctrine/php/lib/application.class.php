<?php
namespace application\discovery\module\cas\implementation\doctrine;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class Application extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;

    /**
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }
}
