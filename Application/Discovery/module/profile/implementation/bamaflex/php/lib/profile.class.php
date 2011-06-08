<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class Profile extends \application\discovery\module\profile\Profile
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_GENDER = 'gender';
    const PROPERTY_BIRTH = 'birth';
    const PROPERTY_ADDRESS = 'address';

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @return int
     */
    function get_gender()
    {
        return $this->get_default_property(self :: PROPERTY_GENDER);
    }

    /**
     * @return Birth
     */
    function get_birth()
    {
        return $this->get_default_property(self :: PROPERTY_BIRTH);
    }

    /**
     * @return multitype:Address
     */
    function get_address()
    {
        return $this->get_default_property(self :: PROPERTY_ADDRESS);
    }

    /**
     * @param int $gender
     */
    function set_gender($gender)
    {
        $this->set_default_property(self :: PROPERTY_GENDER, $gender);
    }

    /**
     * @param Birth $birth
     */
    function set_birth(Birth $birth)
    {
        $this->set_default_property(self :: PROPERTY_BIRTH, $birth);
    }

    /**
     * @param multitype:Address $address
     */
    function set_address($address)
    {
        $this->set_default_property(self :: PROPERTY_ADDRESS, $address);
    }

    /**
     * @param Address $address
     */
    function add_address(Address $address)
    {
        $addresses &= $this->get_address();
        $addresses[] = $address;
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_GENDER;
        $extended_property_names[] = self :: PROPERTY_BIRTH;
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