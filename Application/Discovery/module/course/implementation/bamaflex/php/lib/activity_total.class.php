<?php
namespace application\discovery\module\course\implementation\bamaflex;



class ActivityTotal extends Activity
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_TIME = 'time';

    public function get_time()
    {
        return $this->get_default_property(self :: PROPERTY_TIME);
    }

    public function set_time($time)
    {
        $this->set_default_property(self :: PROPERTY_TIME, $time);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TIME;
        
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
        return implode(' | ', $string);
    }
}
