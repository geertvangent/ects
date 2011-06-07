<?php
namespace application\discovery\module\profile;

use common\libraries\DataClass;

class Communication extends DataClass
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_TYPE = 'type';
    const PROPERTY_DEVICE = 'device';
    const PROPERTY_NUMBER = 'number';

    const TYPE_DOMICILE = 1;
    const TYPE_MOBILE = 2;
    const TYPE_ALTERNATIVE = 3;
    const TYPE_OFFICE = 4;
    const TYPE_EMERGENCY = 5;

    const DEVICE_TELEPHONE = 1;
    const DEVICE_FAX = 2;
    const DEVICE_MOBILE = 3;
    const DEVICE_PAGER = 4;
    const DEVICE_RADIO_TELEPHONE = 5;

    /**
     * @return int
     */
    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * @return int
     */
    function get_device()
    {
        return $this->get_default_property(self :: PROPERTY_DEVICE);
    }

    /**
     * @return string
     */
    function get_number()
    {
        return $this->get_default_property(self :: PROPERTY_NUMBER);
    }

    /**
     * @param int $type
     */
    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     * @param int $device
     */
    function set_device($device)
    {
        $this->set_default_property(self :: PROPERTY_DEVICE, $device);
    }

    /**
     * @param string $number
     */
    function set_number($number)
    {
        $this->set_default_property(self :: PROPERTY_NUMBER, $number);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_DEVICE;
        $extended_property_names[] = self :: PROPERTY_NUMBER;

        return parent :: get_default_property_names($extended_property_names);
    }
}
?>