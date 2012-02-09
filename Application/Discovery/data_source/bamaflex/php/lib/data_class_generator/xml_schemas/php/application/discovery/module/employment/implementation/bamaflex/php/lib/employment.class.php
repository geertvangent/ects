<?php
namespace application\discovery\module\employment\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.employment.implementation.bamaflex
 * @author Magali Gillard
 */
class Employment extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var integer
     */
    const PROPERTY_PERSON_ID = 'person_id';
    /**
     * @var string
     */
    const PROPERTY_YEAR = 'year';
    /**
     * @var string
     */
    const PROPERTY_ASSIGNMENT = 'assignment';
    /**
     * @var string
     */
    const PROPERTY_HOURS = 'hours';
    /**
     * @var string
     */
    const PROPERTY_START_DATE = 'start_date';
    /**
     * @var string
     */
    const PROPERTY_END_DATE = 'end_date';
    /**
     * @var integer
     */
    const PROPERTY_STATE_ID = 'state_id';
    /**
     * @var string
     */
    const PROPERTY_STATE = 'state';
    /**
     * @var string
     */
    const PROPERTY_STATE_CODE = 'state_code';
    /**
     * @var integer
     */
    const PROPERTY_OFFICE_ID = 'office_id';
    /**
     * @var string
     */
    const PROPERTY_OFFICE = 'office';
    /**
     * @var integer
     */
    const PROPERTY_CATEGORY_ID = 'category_id';
    /**
     * @var string
     */
    const PROPERTY_CATEGORY_CODE = 'category_code';
    /**
     * @var string
     */
    const PROPERTY_CATEGORY = 'category';
    /**
     * @var string
     */
    const PROPERTY_CATEGORY_DESCRIPTION = 'category_description';
    /**
     * @var string
     */
    const PROPERTY_DESCRIPTION = 'description';
    /**
     * @var integer
     */
    const PROPERTY_FUND_ID = 'fund_id';
    /**
     * @var string
     */
    const PROPERTY_FUND = 'fund';
    /**
     * @var integer
     */
    const PROPERTY_PAY_SCALE_ID = 'pay_scale_id';
    /**
     * @var string
     */
    const PROPERTY_PAY_SCALE = 'pay_scale';
    /**
     * @var integer
     */
    const PROPERTY_PAY_SCALE_MINIMUM_AGE = 'pay_scale_minimum_age';
    /**
     * @var float
     */
    const PROPERTY_PAY_SCALE_MINIMUM_WAGE = 'pay_scale_minimum_wage';
    /**
     * @var float
     */
    const PROPERTY_PAY_SCALE_MAXIMUM_WAGE = 'pay_scale_maximum_wage';
    /**
     * @var integer
     */
    const PROPERTY_ACTIVE = 'active';
    /**
     * @var integer
     */
    const PROPERTY_CYCLES = 'cycles';


    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_ASSIGNMENT;
        $extended_property_names[] = self :: PROPERTY_HOURS;
        $extended_property_names[] = self :: PROPERTY_START_DATE;
        $extended_property_names[] = self :: PROPERTY_END_DATE;
        $extended_property_names[] = self :: PROPERTY_STATE_ID;
        $extended_property_names[] = self :: PROPERTY_STATE;
        $extended_property_names[] = self :: PROPERTY_STATE_CODE;
        $extended_property_names[] = self :: PROPERTY_OFFICE_ID;
        $extended_property_names[] = self :: PROPERTY_OFFICE;
        $extended_property_names[] = self :: PROPERTY_CATEGORY_ID;
        $extended_property_names[] = self :: PROPERTY_CATEGORY_CODE;
        $extended_property_names[] = self :: PROPERTY_CATEGORY;
        $extended_property_names[] = self :: PROPERTY_CATEGORY_DESCRIPTION;
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;
        $extended_property_names[] = self :: PROPERTY_FUND_ID;
        $extended_property_names[] = self :: PROPERTY_FUND;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE_ID;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE_MINIMUM_AGE;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE_MINIMUM_WAGE;
        $extended_property_names[] = self :: PROPERTY_PAY_SCALE_MAXIMUM_WAGE;
        $extended_property_names[] = self :: PROPERTY_ACTIVE;
        $extended_property_names[] = self :: PROPERTY_CYCLES;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the person_id of this Employment.
     * @return integer The person_id.
     */
    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this Employment.
     * @param integer $person_id
     */
    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the year of this Employment.
     * @return string The year.
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this Employment.
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the assignment of this Employment.
     * @return string The assignment.
     */
    function get_assignment()
    {
        return $this->get_default_property(self :: PROPERTY_ASSIGNMENT);
    }

    /**
     * Sets the assignment of this Employment.
     * @param string $assignment
     */
    function set_assignment($assignment)
    {
        $this->set_default_property(self :: PROPERTY_ASSIGNMENT, $assignment);
    }

    /**
     * Returns the hours of this Employment.
     * @return string The hours.
     */
    function get_hours()
    {
        return $this->get_default_property(self :: PROPERTY_HOURS);
    }

    /**
     * Sets the hours of this Employment.
     * @param string $hours
     */
    function set_hours($hours)
    {
        $this->set_default_property(self :: PROPERTY_HOURS, $hours);
    }

    /**
     * Returns the start_date of this Employment.
     * @return string The start_date.
     */
    function get_start_date()
    {
        return $this->get_default_property(self :: PROPERTY_START_DATE);
    }

    /**
     * Sets the start_date of this Employment.
     * @param string $start_date
     */
    function set_start_date($start_date)
    {
        $this->set_default_property(self :: PROPERTY_START_DATE, $start_date);
    }

    /**
     * Returns the end_date of this Employment.
     * @return string The end_date.
     */
    function get_end_date()
    {
        return $this->get_default_property(self :: PROPERTY_END_DATE);
    }

    /**
     * Sets the end_date of this Employment.
     * @param string $end_date
     */
    function set_end_date($end_date)
    {
        $this->set_default_property(self :: PROPERTY_END_DATE, $end_date);
    }

    /**
     * Returns the state_id of this Employment.
     * @return integer The state_id.
     */
    function get_state_id()
    {
        return $this->get_default_property(self :: PROPERTY_STATE_ID);
    }

    /**
     * Sets the state_id of this Employment.
     * @param integer $state_id
     */
    function set_state_id($state_id)
    {
        $this->set_default_property(self :: PROPERTY_STATE_ID, $state_id);
    }

    /**
     * Returns the state of this Employment.
     * @return string The state.
     */
    function get_state()
    {
        return $this->get_default_property(self :: PROPERTY_STATE);
    }

    /**
     * Sets the state of this Employment.
     * @param string $state
     */
    function set_state($state)
    {
        $this->set_default_property(self :: PROPERTY_STATE, $state);
    }

    /**
     * Returns the state_code of this Employment.
     * @return string The state_code.
     */
    function get_state_code()
    {
        return $this->get_default_property(self :: PROPERTY_STATE_CODE);
    }

    /**
     * Sets the state_code of this Employment.
     * @param string $state_code
     */
    function set_state_code($state_code)
    {
        $this->set_default_property(self :: PROPERTY_STATE_CODE, $state_code);
    }

    /**
     * Returns the office_id of this Employment.
     * @return integer The office_id.
     */
    function get_office_id()
    {
        return $this->get_default_property(self :: PROPERTY_OFFICE_ID);
    }

    /**
     * Sets the office_id of this Employment.
     * @param integer $office_id
     */
    function set_office_id($office_id)
    {
        $this->set_default_property(self :: PROPERTY_OFFICE_ID, $office_id);
    }

    /**
     * Returns the office of this Employment.
     * @return string The office.
     */
    function get_office()
    {
        return $this->get_default_property(self :: PROPERTY_OFFICE);
    }

    /**
     * Sets the office of this Employment.
     * @param string $office
     */
    function set_office($office)
    {
        $this->set_default_property(self :: PROPERTY_OFFICE, $office);
    }

    /**
     * Returns the category_id of this Employment.
     * @return integer The category_id.
     */
    function get_category_id()
    {
        return $this->get_default_property(self :: PROPERTY_CATEGORY_ID);
    }

    /**
     * Sets the category_id of this Employment.
     * @param integer $category_id
     */
    function set_category_id($category_id)
    {
        $this->set_default_property(self :: PROPERTY_CATEGORY_ID, $category_id);
    }

    /**
     * Returns the category_code of this Employment.
     * @return string The category_code.
     */
    function get_category_code()
    {
        return $this->get_default_property(self :: PROPERTY_CATEGORY_CODE);
    }

    /**
     * Sets the category_code of this Employment.
     * @param string $category_code
     */
    function set_category_code($category_code)
    {
        $this->set_default_property(self :: PROPERTY_CATEGORY_CODE, $category_code);
    }

    /**
     * Returns the category of this Employment.
     * @return string The category.
     */
    function get_category()
    {
        return $this->get_default_property(self :: PROPERTY_CATEGORY);
    }

    /**
     * Sets the category of this Employment.
     * @param string $category
     */
    function set_category($category)
    {
        $this->set_default_property(self :: PROPERTY_CATEGORY, $category);
    }

    /**
     * Returns the category_description of this Employment.
     * @return string The category_description.
     */
    function get_category_description()
    {
        return $this->get_default_property(self :: PROPERTY_CATEGORY_DESCRIPTION);
    }

    /**
     * Sets the category_description of this Employment.
     * @param string $category_description
     */
    function set_category_description($category_description)
    {
        $this->set_default_property(self :: PROPERTY_CATEGORY_DESCRIPTION, $category_description);
    }

    /**
     * Returns the description of this Employment.
     * @return string The description.
     */
    function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    /**
     * Sets the description of this Employment.
     * @param string $description
     */
    function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    /**
     * Returns the fund_id of this Employment.
     * @return integer The fund_id.
     */
    function get_fund_id()
    {
        return $this->get_default_property(self :: PROPERTY_FUND_ID);
    }

    /**
     * Sets the fund_id of this Employment.
     * @param integer $fund_id
     */
    function set_fund_id($fund_id)
    {
        $this->set_default_property(self :: PROPERTY_FUND_ID, $fund_id);
    }

    /**
     * Returns the fund of this Employment.
     * @return string The fund.
     */
    function get_fund()
    {
        return $this->get_default_property(self :: PROPERTY_FUND);
    }

    /**
     * Sets the fund of this Employment.
     * @param string $fund
     */
    function set_fund($fund)
    {
        $this->set_default_property(self :: PROPERTY_FUND, $fund);
    }

    /**
     * Returns the pay_scale_id of this Employment.
     * @return integer The pay_scale_id.
     */
    function get_pay_scale_id()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE_ID);
    }

    /**
     * Sets the pay_scale_id of this Employment.
     * @param integer $pay_scale_id
     */
    function set_pay_scale_id($pay_scale_id)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE_ID, $pay_scale_id);
    }

    /**
     * Returns the pay_scale of this Employment.
     * @return string The pay_scale.
     */
    function get_pay_scale()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE);
    }

    /**
     * Sets the pay_scale of this Employment.
     * @param string $pay_scale
     */
    function set_pay_scale($pay_scale)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE, $pay_scale);
    }

    /**
     * Returns the pay_scale_minimum_age of this Employment.
     * @return integer The pay_scale_minimum_age.
     */
    function get_pay_scale_minimum_age()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE_MINIMUM_AGE);
    }

    /**
     * Sets the pay_scale_minimum_age of this Employment.
     * @param integer $pay_scale_minimum_age
     */
    function set_pay_scale_minimum_age($pay_scale_minimum_age)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE_MINIMUM_AGE, $pay_scale_minimum_age);
    }

    /**
     * Returns the pay_scale_minimum_wage of this Employment.
     * @return float The pay_scale_minimum_wage.
     */
    function get_pay_scale_minimum_wage()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE_MINIMUM_WAGE);
    }

    /**
     * Sets the pay_scale_minimum_wage of this Employment.
     * @param float $pay_scale_minimum_wage
     */
    function set_pay_scale_minimum_wage($pay_scale_minimum_wage)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE_MINIMUM_WAGE, $pay_scale_minimum_wage);
    }

    /**
     * Returns the pay_scale_maximum_wage of this Employment.
     * @return float The pay_scale_maximum_wage.
     */
    function get_pay_scale_maximum_wage()
    {
        return $this->get_default_property(self :: PROPERTY_PAY_SCALE_MAXIMUM_WAGE);
    }

    /**
     * Sets the pay_scale_maximum_wage of this Employment.
     * @param float $pay_scale_maximum_wage
     */
    function set_pay_scale_maximum_wage($pay_scale_maximum_wage)
    {
        $this->set_default_property(self :: PROPERTY_PAY_SCALE_MAXIMUM_WAGE, $pay_scale_maximum_wage);
    }

    /**
     * Returns the active of this Employment.
     * @return integer The active.
     */
    function get_active()
    {
        return $this->get_default_property(self :: PROPERTY_ACTIVE);
    }

    /**
     * Sets the active of this Employment.
     * @param integer $active
     */
    function set_active($active)
    {
        $this->set_default_property(self :: PROPERTY_ACTIVE, $active);
    }

    /**
     * Returns the cycles of this Employment.
     * @return integer The cycles.
     */
    function get_cycles()
    {
        return $this->get_default_property(self :: PROPERTY_CYCLES);
    }

    /**
     * Sets the cycles of this Employment.
     * @param integer $cycles
     */
    function set_cycles($cycles)
    {
        $this->set_default_property(self :: PROPERTY_CYCLES, $cycles);
    }


    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
