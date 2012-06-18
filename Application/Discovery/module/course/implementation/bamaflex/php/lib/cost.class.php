<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class Cost extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_TYPE = 'type';
    const PROPERTY_PRICE = 'price';
    
    const TYPE_MATERIAL = 1;
    const TYPE_ADDITIONAL = 2;

    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    function get_type_string()
    {
        return self :: type_string($this->get_type());
    }

    function type_string($type)
    {
        switch ($type)
        {
            case self :: TYPE_ADDITIONAL :
                return 'Additional';
                break;
            case self :: TYPE_MATERIAL :
                return 'Material';
                break;
        
        }
    }

    function get_price()
    {
        return $this->get_default_property(self :: PROPERTY_PRICE);
    }

    function set_price($price)
    {
        $this->set_default_property(self :: PROPERTY_PRICE, $price);
    }

    function get_price_string()
    {
        return $this->get_price() . ' &euro;';
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_PRICE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * @return string
     */
    function __toString()
    {
        $string = array();
        $string[] = $this->get_type();
        $string[] = $this->get_price();
        return implode(' | ', $string);
    }
}
?>