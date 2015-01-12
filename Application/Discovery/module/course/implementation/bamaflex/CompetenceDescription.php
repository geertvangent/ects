<?php
namespace Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex;

class CompetenceDescription extends Competence
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

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
