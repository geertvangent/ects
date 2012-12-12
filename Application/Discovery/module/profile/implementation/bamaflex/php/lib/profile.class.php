<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class Profile extends \application\discovery\module\profile\Profile
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_GENDER = 'gender';
    const PROPERTY_BIRTH = 'birth';
    const PROPERTY_NATIONALITY = 'nationality';
    const PROPERTY_ADDRESS = 'address';
    const PROPERTY_FIRST_UNIVERSITY_COLLEGE = 'first_university_college';
    const PROPERTY_FIRST_UNIVERSITY = 'university';
    const PROPERTY_LEARNING_CREDIT = 'learning_credit';

    private $previous_college;

    private $previous_university;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     *
     * @return the $previous_college
     */
    public function get_previous_college()
    {
        return $this->previous_college;
    }

    /**
     *
     * @param field_type $previous_college
     */
    public function set_previous_college($previous_college)
    {
        $this->previous_college = $previous_college;
    }

    /**
     *
     * @return the $previous_university
     */
    public function get_previous_university()
    {
        return $this->previous_university;
    }

    /**
     *
     * @param field_type $previous_university
     */
    public function set_previous_university($previous_university)
    {
        $this->previous_university = $previous_university;
    }

    /**
     *
     * @return int
     */
    function get_gender()
    {
        return $this->get_default_property(self :: PROPERTY_GENDER);
    }

    /**
     *
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
     *
     * @return Birth
     */
    function get_birth()
    {
        return $this->get_default_property(self :: PROPERTY_BIRTH);
    }

    /**
     *
     * @return multitype:Nationality
     */
    function get_nationality()
    {
        return $this->get_default_property(self :: PROPERTY_NATIONALITY);
    }

    /**
     *
     * @return string
     */
    function get_nationality_string()
    {
        $nationalities = array();
        
        foreach ($this->get_nationality() as $nationality)
        {
            $nationalities[] = $nationality->get_nationality();
        }
        
        return implode(', ', $nationalities);
    }

    /**
     *
     * @return multitype:Address
     */
    function get_address()
    {
        return $this->get_default_property(self :: PROPERTY_ADDRESS);
    }

    /**
     *
     * @param int $gender
     */
    function set_gender($gender)
    {
        $this->set_default_property(self :: PROPERTY_GENDER, $gender);
    }

    /**
     *
     * @param Birth $birth
     */
    function set_birth(Birth $birth)
    {
        $this->set_default_property(self :: PROPERTY_BIRTH, $birth);
    }

    /**
     *
     * @param multitype:Nationality $nationality
     */
    function set_nationality($nationality)
    {
        $this->set_default_property(self :: PROPERTY_NATIONALITY, $nationality);
    }

    /**
     *
     * @param multitype:Address $address
     */
    function set_address($address)
    {
        $this->set_default_property(self :: PROPERTY_ADDRESS, $address);
    }

    /**
     *
     * @param Nationality $nationality
     */
    function add_nationality(Nationality $nationality)
    {
        $nationalities = $this->get_nationality();
        $nationalities[] = $nationality;
        $this->set_nationality($nationalities);
    }

    /**
     *
     * @param Address $address
     */
    function add_address(Address $address)
    {
        $addresses = $this->get_address();
        $addresses[] = $address;
        $this->set_address($addresses);
    }

    function set_first_university_college($first_university_college)
    {
        $this->set_default_property(self :: PROPERTY_FIRST_UNIVERSITY_COLLEGE, $first_university_college);
    }

    function get_first_university_college()
    {
        return $this->get_default_property(self :: PROPERTY_FIRST_UNIVERSITY_COLLEGE);
    }

    function set_first_university($first_university)
    {
        $this->set_default_property(self :: PROPERTY_FIRST_UNIVERSITY, $first_university);
    }

    function get_first_university()
    {
        return $this->get_default_property(self :: PROPERTY_FIRST_UNIVERSITY);
    }

    function get_learning_credit()
    {
        return $this->get_default_property(self :: PROPERTY_LEARNING_CREDIT);
    }

    function set_learning_credit($learning_credit)
    {
        $this->set_default_property(self :: PROPERTY_LEARNING_CREDIT, $learning_credit);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_GENDER;
        $extended_property_names[] = self :: PROPERTY_BIRTH;
        $extended_property_names[] = self :: PROPERTY_NATIONALITY;
        $extended_property_names[] = self :: PROPERTY_ADDRESS;
        $extended_property_names[] = self :: PROPERTY_FIRST_UNIVERSITY_COLLEGE;
        $extended_property_names[] = self :: PROPERTY_FIRST_UNIVERSITY;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }
}
?>