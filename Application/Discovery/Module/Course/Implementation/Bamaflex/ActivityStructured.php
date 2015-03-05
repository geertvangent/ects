<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

class ActivityStructured extends Activity
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_GROUP = 'group';
    const PROPERTY_NAME = 'name';
    const PROPERTY_TIME = 'time';
    const PROPERTY_REMARKS = 'remarks';

    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    public function get_group_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_ID);
    }

    public function set_group_id($group_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_ID, $group_id);
    }

    public function get_group()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP);
    }

    public function set_group($group)
    {
        $this->set_default_property(self :: PROPERTY_GROUP, $group);
    }

    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    public function get_time()
    {
        return $this->get_default_property(self :: PROPERTY_TIME);
    }

    public function set_time($time)
    {
        $this->set_default_property(self :: PROPERTY_TIME, $time);
    }

    public function get_remarks()
    {
        return $this->get_default_property(self :: PROPERTY_REMARKS);
    }

    public function set_remarks($remarks)
    {
        $this->set_default_property(self :: PROPERTY_REMARKS, $remarks);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_TIME;
        $extended_property_names[] = self :: PROPERTY_REMARKS;
        
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
