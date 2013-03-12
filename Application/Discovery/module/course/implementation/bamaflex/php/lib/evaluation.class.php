<?php
namespace application\discovery\module\course\implementation\bamaflex;


use application\discovery\DiscoveryItem;

class Evaluation extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_DESCRIPTION = 'description';

    public function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    public function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
//         return DataManager :: get_instance();
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
