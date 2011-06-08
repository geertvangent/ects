<?php
namespace application\discovery\module\profile;

use application\discovery\DiscoveryDataManager;

use common\libraries\DataClass;

class Email extends DataClass
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_TYPE = 'type';
    const PROPERTY_ADDRESS = 'address';

    const TYPE_PRIVATE = 1;
    const TYPE_OFFICIAL = 2;
    const TYPE_OFFICIAL_DISCONTINUED = 3;
    const TYPE_DISCONTINUED = 4;

    /**
     * @return int
     */
    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * @return string
     */
    function get_address()
    {
        return $this->get_default_property(self :: PROPERTY_ADDRESS);
    }

    /**
     * @param int $type
     */
    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     * @param string $address
     */
    function set_address($address)
    {
        $this->set_default_property(self :: PROPERTY_ADDRESS, $address);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_ADDRESS;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }
}
?>