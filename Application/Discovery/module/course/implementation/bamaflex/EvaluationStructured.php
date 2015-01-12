<?php
namespace Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex;

class EvaluationStructured extends Evaluation
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_TRY = 'try';
    const PROPERTY_MOMENT_ID = 'moment_id';
    const PROPERTY_MOMENT = 'moment';
    const PROPERTY_TYPE_ID = 'type_id';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_PERMANENT = 'permanent';
    const PROPERTY_PERCENTAGE = 'percentage';

    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    public function get_try()
    {
        return $this->get_default_property(self :: PROPERTY_TRY);
    }

    public function set_try($try)
    {
        $this->set_default_property(self :: PROPERTY_TRY, $try);
    }

    public function get_moment_id()
    {
        return $this->get_default_property(self :: PROPERTY_MOMENT_ID);
    }

    public function set_moment_id($moment_id)
    {
        $this->set_default_property(self :: PROPERTY_MOMENT_ID, $moment_id);
    }

    public function get_moment()
    {
        return $this->get_default_property(self :: PROPERTY_MOMENT);
    }

    public function set_moment($moment)
    {
        $this->set_default_property(self :: PROPERTY_MOMENT, $moment);
    }

    public function get_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE_ID);
    }

    public function set_type_id($type_id)
    {
        $this->set_default_property(self :: PROPERTY_TYPE_ID, $type_id);
    }

    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    public function get_permanent()
    {
        return $this->get_default_property(self :: PROPERTY_PERMANENT);
    }

    public function set_permanent($permanent)
    {
        $this->set_default_property(self :: PROPERTY_PERMANENT, $permanent);
    }

    public function get_percentage()
    {
        return $this->get_default_property(self :: PROPERTY_PERCENTAGE);
    }

    public function set_percentage($percentage)
    {
        $this->set_default_property(self :: PROPERTY_PERCENTAGE, $percentage);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_MOMENT_ID;
        $extended_property_names[] = self :: PROPERTY_MOMENT;
        $extended_property_names[] = self :: PROPERTY_TRY;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_PERCENTAGE;
        $extended_property_names[] = self :: PROPERTY_PERMANENT;
        
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
