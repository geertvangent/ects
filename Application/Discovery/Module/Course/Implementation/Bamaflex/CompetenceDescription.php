<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

class CompetenceDescription extends Competence
{

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
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
