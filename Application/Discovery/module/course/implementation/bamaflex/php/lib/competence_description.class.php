<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class CompetenceDescription extends Competence
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

    /**
     *
     * @return string
     */
    function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
?>