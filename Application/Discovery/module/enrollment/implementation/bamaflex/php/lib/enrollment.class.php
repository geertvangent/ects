<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class Enrollment extends \application\discovery\module\enrollment\Enrollment
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_CONTRACT_TYPE = 'contract_type';
    const PROPERTY_TRAJECTORY_TYPE = 'trajectory_type';
    const PROPERTY_TRAJECTORY = 'trajectory';
    const PROPERTY_OPTION_CHOICE = 'option_choice';
    const PROPERTY_GRADUATION_OPTION = 'graduation_option';

    const CONTRACT_TYPE_DEGREE = 1;
    const CONTRACT_TYPE_CREDIT = 2;
    const CONTRACT_TYPE_EXAM_DEGREE = 3;
    const CONTRACT_TYPE_EXAM_CREDIT = 4;

    const TRAJECTORY_TYPE_TEMPLATE = 1;
    const TRAJECTORY_TYPE_PERSONAL = 2;
    const TRAJECTORY_TYPE_INDIVIDUAL = 3;
    const TRAJECTORY_TYPE_UNKNOWN = 4;

    /**
     * @return string
     */
    function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    /**
     * @return int
     */
    function get_contract_type()
    {
        return $this->get_default_property(self :: PROPERTY_CONTRACT_TYPE);
    }

    /**
     * @return string
     */
    function get_contract_type_string()
    {
        switch ($this->get_contract_type())
        {
            case self :: CONTRACT_TYPE_DEGREE :
                return 'Degree';
                break;
            case self :: CONTRACT_TYPE_CREDIT :
                return 'Credit';
                break;
            case self :: CONTRACT_TYPE_EXAM_DEGREE :
                return 'ExamDegree';
                break;
            case self :: CONTRACT_TYPE_EXAM_CREDIT :
                return 'ExamCredit';
                break;
        }
    }

    /**
     * @return int
     */
    function get_trajectory_type()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_TYPE);
    }

    /**
     * @return string
     */
    function get_trajectory_type_string()
    {
        switch ($this->get_contract_type())
        {
            case self :: TRAJECTORY_TYPE_TEMPLATE :
                return 'Template';
                break;
            case self :: TRAJECTORY_TYPE_PERSONAL :
                return 'Personal';
                break;
            case self :: TRAJECTORY_TYPE_INDIVIDUAL :
                return 'Individual';
                break;
            case self :: TRAJECTORY_TYPE_UNKNOWN :
                return 'Unknown';
                break;
        }
    }

    /**
     * @return string
     */
    function get_trajectory()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY);
    }

    /**
     * @return string
     */
    function get_unified_trajectory()
    {
        if ($this->get_trajectory_type() == self :: TRAJECTORY_TYPE_TEMPLATE)
        {
            return $this->get_trajectory();
        }
        else
        {
            return $this->get_trajectory_type_string();
        }
    }

    /**
     * @return string
     */
    function get_option_choice()
    {
        return $this->get_default_property(self :: PROPERTY_OPTION_CHOICE);
    }

    /**
     * @return string
     */
    function get_graduation_option()
    {
        return $this->get_default_property(self :: PROPERTY_GRADUATION_OPTION);
    }

    /**
     * @return string
     */
    function get_unified_option()
    {
        $options = array();

        if ($this->get_option_choice())
        {
            $options[] = $this->get_option_choice();
        }

        if ($this->get_graduation_option())
        {
            $options[] = $this->get_graduation_option();
        }

        return implode(' - ', $options);
    }

    /**
     * @param string $faculty
     */
    function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    /**
     * @param int $contract_type
     */
    function set_contract_type($contract_type)
    {
        $this->set_default_property(self :: PROPERTY_CONTRACT_TYPE, $contract_type);
    }

    /**
     * @param int $trajectory_type
     */
    function set_trajectory_type($trajectory_type)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_TYPE, $trajectory_type);
    }

    /**
     * @param string $trajectory
     */
    function set_trajectory($trajectory)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY, $trajectory);
    }

    /**
     * @param string $option_choice
     */
    function set_option_choice($option_choice)
    {
        $this->set_default_property(self :: PROPERTY_OPTION_CHOICE, $option_choice);
    }

    /**
     * @param string $graduation_option
     */
    function set_graduation_option($graduation_option)
    {
        $this->set_default_property(self :: PROPERTY_GRADUATION_OPTION, $graduation_option);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_CONTRACT_TYPE;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY;
        $extended_property_names[] = self :: PROPERTY_OPTION_CHOICE;
        $extended_property_names[] = self :: PROPERTY_GRADUATION_OPTION;

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