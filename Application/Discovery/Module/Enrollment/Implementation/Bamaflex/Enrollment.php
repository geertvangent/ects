<?php
namespace Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex;

use Ehb\Application\Discovery\Module\Career\DataManager;
use Chamilo\Libraries\Platform\Translation;

class Enrollment extends \Ehb\Application\Discovery\Module\Enrollment\Enrollment
{
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_CONTRACT_TYPE = 'contract_type';
    const PROPERTY_CONTRACT_ID = 'contract_id';
    const PROPERTY_TRAJECTORY_TYPE = 'trajectory_type';
    const PROPERTY_TRAJECTORY = 'trajectory';
    const PROPERTY_OPTION_CHOICE = 'option_choice';
    const PROPERTY_GRADUATION_OPTION = 'graduation_option';
    const PROPERTY_DISTINCTION = 'distinction';
    const PROPERTY_EXCHANGE_TYPE = 'exchange_type';
    const PROPERTY_GENERATION_STUDENT = 'generation_student';
    const CONTRACT_TYPE_ALL = 0;
    const CONTRACT_TYPE_DEGREE = 1;
    const CONTRACT_TYPE_CREDIT = 2;
    const CONTRACT_TYPE_EXAM_DEGREE = 3;
    const CONTRACT_TYPE_EXAM_CREDIT = 4;
    const CONTRACT_TYPE_OLD_DEGREE = 5;
    const TRAJECTORY_TYPE_TEMPLATE = 1;
    const TRAJECTORY_TYPE_PERSONAL = 2;
    const TRAJECTORY_TYPE_INDIVIDUAL = 3;
    const TRAJECTORY_TYPE_UNKNOWN = 4;
    const RESULT_TOLERATED = 6;
    const RESULT_GRADUALLY_TOLERATED = 7;
    const RESULT_STRUCK = 8;
    const DISTINCTION_NONE = 1;
    // Voldoende wijze
    const DISTINCTION_SUFFICIENT = 2;
    // Onderscheiding
    const DISTINCTION_GOOD = 3;
    // Grote onderscheiding
    const DISTINCTION_VERY_GOOD = 4;
    // Grootste onderscheiding
    const DISTINCTION_EXCELLENT = 5;
    const EXCHANGE_TYPE_NONE = 1;
    const EXCHANGE_TYPE_INCOMING = 2;
    const EXCHANGE_TYPE_OUTGOING = 3;

    /**
     *
     * @return int
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     *
     * @return string
     */
    public function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    public function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    /**
     *
     * @return int
     */
    public function get_contract_type()
    {
        return $this->get_default_property(self :: PROPERTY_CONTRACT_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_contract_type_string()
    {
        return self :: contract_type_string($this->get_contract_type());
    }

    /**
     *
     * @return string
     */
    public static function contract_type_string($contract_type)
    {
        switch ($contract_type)
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
            case self :: CONTRACT_TYPE_OLD_DEGREE :
                return 'OldDegree';
                break;
        }
    }

    /**
     *
     * @return int
     */
    public function get_contract_id()
    {
        return $this->get_default_property(self :: PROPERTY_CONTRACT_ID);
    }

    /**
     *
     * @return int
     */
    public function get_trajectory_type()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_trajectory_type_string()
    {
        switch ($this->get_trajectory_type())
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
     *
     * @return string
     */
    public function get_trajectory()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY);
    }

    /**
     *
     * @return string
     */
    public function get_unified_trajectory()
    {
        if ($this->get_trajectory_type() == self :: TRAJECTORY_TYPE_TEMPLATE)
        {
            return $this->get_trajectory();
        }
        else
        {
            return Translation :: get($this->get_trajectory_type_string());
        }
    }

    /**
     *
     * @return string
     */
    public function get_option_choice()
    {
        return $this->get_default_property(self :: PROPERTY_OPTION_CHOICE);
    }

    /**
     *
     * @return string
     */
    public function get_graduation_option()
    {
        return $this->get_default_property(self :: PROPERTY_GRADUATION_OPTION);
    }

    /**
     *
     * @return string
     */
    public function get_unified_option()
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
     *
     * @return int
     */
    public function get_distinction()
    {
        return $this->get_default_property(self :: PROPERTY_DISTINCTION);
    }

    /**
     *
     * @return string
     */
    public function get_distinction_string()
    {
        return self :: distinction_string($this->get_distinction());
    }

    public function has_distinction()
    {
        return ! is_null($this->get_distinction());
    }

    /**
     *
     * @return string
     */
    public static function distinction_string($distinction)
    {
        switch ($distinction)
        {
            case self :: DISTINCTION_NONE :
                return 'NoDistinction';
                break;
            case self :: DISTINCTION_SUFFICIENT :
                return 'Sufficient';
                break;
            case self :: DISTINCTION_GOOD :
                return 'Good';
                break;
            case self :: DISTINCTION_VERY_GOOD :
                return 'VeryGood';
                break;
            case self :: DISTINCTION_EXCELLENT :
                return 'Excellent';
                break;
        }
    }

    /**
     *
     * @return int
     */
    public function get_exchange_type()
    {
        return $this->get_default_property(self :: PROPERTY_EXCHANGE_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_exchange_type_string()
    {
        return self :: exchange_type_string($this->get_exchange_type());
    }

    /**
     *
     * @return string
     */
    public static function exchange_type_string($exchange_type)
    {
        switch ($exchange_type)
        {
            case self :: EXCHANGE_TYPE_NONE :
                return 'None';
                break;
            case self :: EXCHANGE_TYPE_INCOMING :
                return 'Incoming';
                break;
            case self :: EXCHANGE_TYPE_OUTGOING :
                return 'Outgoing';
                break;
        }
    }

    public function get_generation_student()
    {
        return $this->get_default_property(self :: PROPERTY_GENERATION_STUDENT);
    }

    public function set_generation_student($generation_student)
    {
        $this->set_default_property(self :: PROPERTY_GENERATION_STUDENT, $generation_student);
    }

    /**
     *
     * @param int $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     *
     * @param string $faculty
     */
    public function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    public function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    /**
     *
     * @param int $contract_type
     */
    public function set_contract_type($contract_type)
    {
        $this->set_default_property(self :: PROPERTY_CONTRACT_TYPE, $contract_type);
    }

    /**
     *
     * @param int $contract_id
     */
    public function set_contract_id($contract_id)
    {
        $this->set_default_property(self :: PROPERTY_CONTRACT_ID, $contract_id);
    }

    /**
     *
     * @param int $trajectory_type
     */
    public function set_trajectory_type($trajectory_type)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_TYPE, $trajectory_type);
    }

    /**
     *
     * @param string $trajectory
     */
    public function set_trajectory($trajectory)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY, $trajectory);
    }

    /**
     *
     * @param string $option_choice
     */
    public function set_option_choice($option_choice)
    {
        $this->set_default_property(self :: PROPERTY_OPTION_CHOICE, $option_choice);
    }

    /**
     *
     * @param string $graduation_option
     */
    public function set_graduation_option($graduation_option)
    {
        $this->set_default_property(self :: PROPERTY_GRADUATION_OPTION, $graduation_option);
    }

    /**
     *
     * @param int $distinction
     */
    public function set_distinction($distinction)
    {
        $this->set_default_property(self :: PROPERTY_DISTINCTION, $distinction);
    }

    /**
     *
     * @return string
     */
    public function get_result_string()
    {
        return self :: result_string($this->get_result());
    }

    /**
     *
     * @return string
     */
    public static function result_string($result)
    {
        switch ($result)
        {
            case self :: RESULT_TOLERATED :
                return 'Tolerated';
                break;
            case self :: RESULT_GRADUALLY_TOLERATED :
                return 'GraduallyTolerated';
                break;
            case self :: RESULT_STRUCK :
                return 'Struck';
                break;
            default :
                return parent :: result_string($result);
                break;
        }
    }

    /**
     *
     * @return multitype:string
     */
    public static function get_results()
    {
        $results = parent :: get_results();
        $results[] = self :: RESULT_TOLERATED;
        return $results;
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_CONTRACT_TYPE;
        $extended_property_names[] = self :: PROPERTY_CONTRACT_ID;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY;
        $extended_property_names[] = self :: PROPERTY_OPTION_CHOICE;
        $extended_property_names[] = self :: PROPERTY_GRADUATION_OPTION;
        $extended_property_names[] = self :: PROPERTY_DISTINCTION;

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
        $string[] = $this->get_year();
        $string[] = $this->get_training();

        if ($this->get_unified_option())
        {
            $string[] = $this->get_unified_option();
        }

        return implode(' | ', $string);
    }

    public function get_training_object()
    {
        return DataManager :: get_instance($this->get_instance())->retrieve_training(
            $this->get_source(),
            $this->get_training_id());
    }
}
