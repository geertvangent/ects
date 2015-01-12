<?php
namespace Chamilo\Application\Discovery\Module\Profile;

use Chamilo\Libraries\Storage\DataClass\DataClass;

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
     *
     * @return int
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_type_string()
    {
        switch ($this->get_type())
        {
            case self :: TYPE_PRIVATE :
                return 'Private';
                break;
            case self :: TYPE_OFFICIAL :
                return 'Official';
                break;
            case self :: TYPE_OFFICIAL_DISCONTINUED :
                return 'OfficialDiscontinued';
                break;
            case self :: TYPE_DISCONTINUED :
                return 'Discontinued';
                break;
        }
    }

    /**
     *
     * @return string
     */
    public function get_address()
    {
        return $this->get_default_property(self :: PROPERTY_ADDRESS);
    }

    /**
     *
     * @param int $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @param string $address
     */
    public function set_address($address)
    {
        $this->set_default_property(self :: PROPERTY_ADDRESS, $address);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_ADDRESS;
        
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
}
