<?php
namespace Ehb\Application\Discovery\Module\StudentMaterials\Implementation\Bamaflex;

class MaterialDescription extends Material
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
