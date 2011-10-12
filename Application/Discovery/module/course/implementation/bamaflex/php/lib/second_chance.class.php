<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class SecondChance extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_EXAM = 'exam';
    const PROPERTY_ENROLLMENT = 'enrollment';

    function get_exam()
    {
        return $this->get_default_property(self :: PROPERTY_EXAM);
    }

    function set_exam($exam)
    {
        $this->set_default_property(self :: PROPERTY_EXAM, $exam);
    }

    function get_enrollment()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT);
    }

    function set_enrollment($enrollment)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT, $enrollment);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_EXAM;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * @return string
     */
    function __toString()
    {
        $string = array();
        $string[] = $this->get_exam();
        $string[] = $this->get_enrollment();
        return implode(' | ', $string);
    }
}
?>