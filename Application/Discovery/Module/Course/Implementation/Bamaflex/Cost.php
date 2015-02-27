<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Ehb\Application\Discovery\DiscoveryItem;

class Cost extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_TYPE = 'type';
    const PROPERTY_PRICE = 'price';
    const TYPE_MATERIAL = 1;
    const TYPE_ADDITIONAL = 2;

    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    public function get_type_string()
    {
        return self :: type_string($this->get_type());
    }

    public function type_string($type)
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

    public function get_price()
    {
        return $this->get_default_property(self :: PROPERTY_PRICE);
    }

    public function set_price($price)
    {
        $this->set_default_property(self :: PROPERTY_PRICE, $price);
    }

    public function get_price_string()
    {
        return $this->get_price() . ' &euro;';
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_PRICE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

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
        $string[] = $this->get_type();
        $string[] = $this->get_price();
        return implode(' | ', $string);
    }
}
