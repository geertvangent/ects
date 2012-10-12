<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.profile.implementation.bamaflex
 * @author Hans De Bisschop
 */
class LearningCredit extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * @var integer
     */
    const PROPERTY_PERSON_ID = 'person_id';
    /**
     * @var string
     */
    const PROPERTY_DATE = 'date';
    /**
     * @var integer
     */
    const PROPERTY_LEARNING_CREDIT = 'learning_credit';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_DATE;
        $extended_property_names[] = self :: PROPERTY_LEARNING_CREDIT;
        
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
     * Returns the person_id of this LearningCredit.
     * @return integer The person_id.
     */
    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this LearningCredit.
     * @param integer $person_id
     */
    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the date of this LearningCredit.
     * @return string The date.
     */
    function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     * Sets the date of this LearningCredit.
     * @param string $date
     */
    function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     * Returns the learning_credit of this LearningCredit.
     * @return integer The learning_credit.
     */
    function get_learning_credit()
    {
        return $this->get_default_property(self :: PROPERTY_LEARNING_CREDIT);
    }

    /**
     * Sets the learning_credit of this LearningCredit.
     * @param integer $learning_credit
     */
    function set_learning_credit($learning_credit)
    {
        $this->set_default_property(self :: PROPERTY_LEARNING_CREDIT, $learning_credit);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    function get_html()
    {
        if (is_null($this->get_learning_credit()))
        {
            return '140';
        }
        elseif ($this->get_learning_credit() <= 0)
        {
            return '<span style="color:#b54141">' . $this->get_learning_credit() . '</span>';
        }
        else
        {
            return '<span style="color:#139a4f">' . $this->get_learning_credit() . '</span>';
        }
    }
}
