<?php
namespace Ehb\Application\Discovery\Module\Enrollment;

use Ehb\Application\Discovery\DiscoveryItem;

class Enrollment extends DiscoveryItem
{
    const PROPERTY_YEAR = 'year';
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_RESULT = 'result';
    const PROPERTY_PERSON_ID = 'person_id';
    const RESULT_GRADUATED = 1;
    const RESULT_PASSED = 2;
    const RESULT_FAILED = 3;
    const RESULT_NOT_RELEVANT = 4;
    const RESULT_NO_DATA = 5;

    /**
     *
     * @return string
     */
    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     *
     * @return string
     */
    public function get_training()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING);
    }

    public function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     *
     * @return string
     */
    public function get_result()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT);
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
            case self :: RESULT_GRADUATED :
                return 'Graduated';
                break;
            case self :: RESULT_PASSED :
                return 'Passed';
                break;
            case self :: RESULT_FAILED :
                return 'Failed';
                break;
            case self :: RESULT_NOT_RELEVANT :
                return 'NotRelevant';
                break;
            case self :: RESULT_NO_DATA :
                return 'NoDataAvailable';
                break;
        }
    }

    /**
     *
     * @return multitype:string
     */
    public static function get_results()
    {
        return array(self :: RESULT_PASSED, self :: RESULT_FAILED, self :: RESULT_NOT_RELEVANT, self :: RESULT_NO_DATA);
    }

    /**
     *
     * @return boolean
     */
    public function is_special_result()
    {
        return ($this->get_result() != self :: RESULT_NOT_RELEVANT);
    }

    /**
     *
     * @param string $year
     */
    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     *
     * @param string $training
     */
    public function set_training($training)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING, $training);
    }

    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    public function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     *
     * @param string $result
     */
    public function set_result($result)
    {
        $this->set_default_property(self :: PROPERTY_RESULT, $result);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_TRAINING;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_RESULT;
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
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
        return implode(' | ', $string);
    }
}
