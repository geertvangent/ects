<?php
namespace application\discovery\module\employment;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class Employment extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_PAY_SCALE = 'pay_scale';
    const PROPERTY_PAY_SCALE_MINIMUN_WAGE = 'pay_scale_minimum_wage';
    const PROPERTY_PAY_SCALE_MAXIMUN_WAGE = 'pay_scale_maximum_wage';

    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    function get_start_date()
    {
        return $this->get_default_property(self :: PROPERTY_START_DATE);
    }

    function get_end_date()
    {
        return $this->get_default_property(self :: PROPERTY_END_DATE);
    }

    function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    function get_pay_scale()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE);
    }

    function get_pay_scale_minimum_wage()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE_MINIMUN_WAGE);
    }

    function get_pay_scale_maximum_wage()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE_MAXIMUN_WAGE);
    }

    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    function set_start_date($start_date)
    {
        $this->set_default_property(self :: PROPERTY_START_DATE, $start_date);
    }

    function set_end_date($end_date)
    {
        $this->set_default_property(self :: PROPERTY_END_DATE, $end_date);
    }

    function set_pay_scale($pay_scale)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE, $pay_scale);
    }

    function set_pay_scale_minimum_wage($pay_scale_minimum_wage)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE_MINIMUN_WAGE, $pay_scale_minimum_wage);
    }

    function set_pay_scale_maximum_wage($pay_scale_maximum_wage)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE_MAXIMUN_WAGE, $pay_scale_maximum_wage);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_START_DATE;
        $extended_property_names[] = self :: PROPERTY_END_DATE;
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE_MINIMUN_WAGE;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE_MAXIMUN_WAGE;
        
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