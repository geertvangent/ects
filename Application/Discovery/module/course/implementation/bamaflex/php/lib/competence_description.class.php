<?php
namespace application\discovery\module\course\implementation\bamaflex;



class CompetenceDescription extends Competence
{
    const CLASS_NAME = __CLASS__;

    /**
     *
     * @return DataManagerInterface
     */
    function get_data_manager()
    {
        // return DataManager :: get_instance();
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
