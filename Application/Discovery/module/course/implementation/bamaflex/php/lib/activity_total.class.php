<?php
namespace application\discovery\module\course\implementation\bamaflex;



class ActivityTotal extends Activity
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_TIME = 'time';

    function get_time()
    {
        return $this->get_default_property(self :: PROPERTY_TIME);
    }

    function set_time($time)
    {
        $this->set_default_property(self :: PROPERTY_TIME, $time);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TIME;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
