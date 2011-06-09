<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class Enrollment extends \application\discovery\module\enrollment\Enrollment
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_GENDER = 'gender';
    const PROPERTY_BIRTH = 'birth';
    const PROPERTY_NATIONALITY = 'nationality';
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
     * @return string
     */
    function get_gender_string()
    {
        switch ($this->get_gender())
        {
            case self :: GENDER_MALE :
                return 'Male';
                break;
            case self :: GENDER_FEMALE :
                return 'Female';
                break;
            default :
                return 'Unknown';
        }
    }

    /**
     * @return Birth
     */
    function get_birth()
    {
        return $this->get_default_property(self :: PROPERTY_BIRTH);
    }

    /**
     * @return multitype:Nationality
     */
    function get_nationality()
    {
        return $this->get_default_property(self :: PROPERTY_NATIONALITY);
    }

    /**
     * @return string
     */
    function get_nationality_string()
    {
        $nationalities = array();

        foreach($this->get_nationality() as $nationality)
        {
            $nationalities[] = $nationality->get_nationality();
        }

        return implode(', ', $nationalities);
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
     * @param multitype:Nationality $nationality
     */
    function set_nationality($nationality)
    {
        $this->set_default_property(self :: PROPERTY_NATIONALITY, $nationality);
    }

    /**
     * @param multitype:Address $address
     */
    function set_address($address)
    {
        $this->set_default_property(self :: PROPERTY_ADDRESS, $address);
    }

    /**
     * @param Nationality $nationality
     */
    function add_nationality(Nationality $nationality)
    {
        $nationalities = $this->get_nationality();
        $nationalities[] = $nationality;
        $this->set_nationality($nationalities);
    }

    /**
     * @param Address $address
     */
    function add_address(Address $address)
    {
        $addresses = $this->get_address();
        $addresses[] = $address;
        $this->set_address($addresses);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_GENDER;
        $extended_property_names[] = self :: PROPERTY_BIRTH;
        $extended_property_names[] = self :: PROPERTY_NATIONALITY;
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