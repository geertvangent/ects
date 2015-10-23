<?php
namespace Ehb\Application\Discovery\Module\Training;

use Ehb\Application\Discovery\DiscoveryItem;

class Training extends DiscoveryItem
{
    const PROPERTY_YEAR = 'year';
    const PROPERTY_NAME = 'name';
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';

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
    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    public function get_start_date()
    {
        return $this->get_default_property(self :: PROPERTY_START_DATE);
    }

    public function get_end_date()
    {
        return $this->get_default_property(self :: PROPERTY_END_DATE);
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
     * @param string $name
     */
    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    public function set_start_date($start_date)
    {
        $this->set_default_property(self :: PROPERTY_START_DATE, $start_date);
    }

    public function set_end_date($end_date)
    {
        $this->set_default_property(self :: PROPERTY_END_DATE, $end_date);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_START_DATE;
        $extended_property_names[] = self :: PROPERTY_END_DATE;

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
        $string[] = $this->get_name();
        return implode(' | ', $string);
    }
}
