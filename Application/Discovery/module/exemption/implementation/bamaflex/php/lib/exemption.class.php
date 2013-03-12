<?php
namespace application\discovery\module\exemption\implementation\bamaflex;


use application\discovery\DiscoveryItem;
use common\libraries\Utilities;

/**
 * application.discovery.module.exemption.implementation.bamaflex
 * 
 * @author Magali Gillard
 */
class Exemption extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    /**
     *
     * @var integer
     */
    const PROPERTY_ENROLLMENT_ID = 'enrollment_id';
    /**
     *
     * @var string
     */
    const PROPERTY_YEAR = 'year';
    /**
     *
     * @var integer
     */
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    /**
     *
     * @var string
     */
    const PROPERTY_PROGRAMME_NAME = 'programme_name';
    /**
     *
     * @var integer
     */
    const PROPERTY_TYPE_ID = 'type_id';
    /**
     *
     * @var string
     */
    const PROPERTY_TYPE = 'type';
    /**
     *
     * @var string
     */
    const PROPERTY_RESULT = 'result';
    /**
     *
     * @var integer
     */
    const PROPERTY_STATE = 'state';
    /**
     *
     * @var string
     */
    const PROPERTY_CREDITS = 'credits';
    /**
     *
     * @var string
     */
    const PROPERTY_PROOF = 'proof';
    /**
     *
     * @var string
     */
    const PROPERTY_DATE_REQUESTED = 'date_requested';
    /**
     *
     * @var string
     */
    const PROPERTY_DATE_CLOSED = 'date_closed';
    /**
     *
     * @var string
     */
    const PROPERTY_REMARKS_PUBLIC = 'remarks_public';
    /**
     *
     * @var string
     */
    const PROPERTY_REMARKS_PRIVATE = 'remarks_private';
    /**
     *
     * @var string
     */
    const PROPERTY_MOTIVATION = 'motivation';
    /**
     *
     * @var integer
     */
    const PROPERTY_EXTERNAL_ID = 'external_id';
    /**
     *
     * @var string
     */
    const PROPERTY_EXTERNAL = 'external';
    const STATE_PENDING = 1;
    const STATE_ACCEPTED = 2;
    const STATE_REFUSED = 3;

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_NAME;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_RESULT;
        $extended_property_names[] = self :: PROPERTY_STATE;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_PROOF;
        $extended_property_names[] = self :: PROPERTY_DATE_REQUESTED;
        $extended_property_names[] = self :: PROPERTY_DATE_CLOSED;
        $extended_property_names[] = self :: PROPERTY_REMARKS_PUBLIC;
        $extended_property_names[] = self :: PROPERTY_REMARKS_PRIVATE;
        $extended_property_names[] = self :: PROPERTY_MOTIVATION;
        $extended_property_names[] = self :: PROPERTY_EXTERNAL_ID;
        $extended_property_names[] = self :: PROPERTY_EXTERNAL;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     * Returns the enrollment_id of this Exemption.
     * 
     * @return integer The enrollment_id.
     */
    public function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment_id of this Exemption.
     * 
     * @param integer $enrollment_id
     */
    public function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the year of this Exemption.
     * 
     * @return string The year.
     */
    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this Exemption.
     * 
     * @param string $year
     */
    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the programme_id of this Exemption.
     * 
     * @return integer The programme_id.
     */
    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    /**
     * Sets the programme_id of this Exemption.
     * 
     * @param integer $programme_id
     */
    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    /**
     * Returns the programme_name of this Exemption.
     * 
     * @return string The programme_name.
     */
    public function get_programme_name()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_NAME);
    }

    /**
     * Sets the programme_name of this Exemption.
     * 
     * @param string $programme_name
     */
    public function set_programme_name($programme_name)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_NAME, $programme_name);
    }

    /**
     * Returns the type_id of this Exemption.
     * 
     * @return integer The type_id.
     */
    public function get_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE_ID);
    }

    /**
     * Sets the type_id of this Exemption.
     * 
     * @param integer $type_id
     */
    public function set_type_id($type_id)
    {
        $this->set_default_property(self :: PROPERTY_TYPE_ID, $type_id);
    }

    /**
     * Returns the type of this Exemption.
     * 
     * @return string The type.
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * Sets the type of this Exemption.
     * 
     * @param string $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     * Returns the result of this Exemption.
     * 
     * @return string The result.
     */
    public function get_result()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT);
    }

    /**
     * Sets the result of this Exemption.
     * 
     * @param string $result
     */
    public function set_result($result)
    {
        $this->set_default_property(self :: PROPERTY_RESULT, $result);
    }

    /**
     * Returns the state of this Exemption.
     * 
     * @return integer The state.
     */
    public function get_state()
    {
        return $this->get_default_property(self :: PROPERTY_STATE);
    }

    /**
     * Sets the state of this Exemption.
     * 
     * @param integer $state
     */
    public function set_state($state)
    {
        $this->set_default_property(self :: PROPERTY_STATE, $state);
    }

    /**
     * Returns the credits of this Exemption.
     * 
     * @return string The credits.
     */
    public function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    /**
     * Sets the credits of this Exemption.
     * 
     * @param string $credits
     */
    public function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    /**
     * Returns the proof of this Exemption.
     * 
     * @return string The proof.
     */
    public function get_proof()
    {
        return $this->get_default_property(self :: PROPERTY_PROOF);
    }

    /**
     * Sets the proof of this Exemption.
     * 
     * @param string $proof
     */
    public function set_proof($proof)
    {
        $this->set_default_property(self :: PROPERTY_PROOF, $proof);
    }

    /**
     * Returns the date_requested of this Exemption.
     * 
     * @return string The date_requested.
     */
    public function get_date_requested()
    {
        return $this->get_default_property(self :: PROPERTY_DATE_REQUESTED);
    }

    /**
     * Sets the date_requested of this Exemption.
     * 
     * @param string $date_requested
     */
    public function set_date_requested($date_requested)
    {
        $this->set_default_property(self :: PROPERTY_DATE_REQUESTED, $date_requested);
    }

    /**
     * Returns the date_closed of this Exemption.
     * 
     * @return string The date_closed.
     */
    public function get_date_closed()
    {
        return $this->get_default_property(self :: PROPERTY_DATE_CLOSED);
    }

    /**
     * Sets the date_closed of this Exemption.
     * 
     * @param string $date_closed
     */
    public function set_date_closed($date_closed)
    {
        $this->set_default_property(self :: PROPERTY_DATE_CLOSED, $date_closed);
    }

    /**
     * Returns the remarks_public of this Exemption.
     * 
     * @return string The remarks_public.
     */
    public function get_remarks_public()
    {
        return $this->get_default_property(self :: PROPERTY_REMARKS_PUBLIC);
    }

    /**
     * Sets the remarks_public of this Exemption.
     * 
     * @param string $remarks_public
     */
    public function set_remarks_public($remarks_public)
    {
        $this->set_default_property(self :: PROPERTY_REMARKS_PUBLIC, $remarks_public);
    }

    /**
     * Returns the remarks_private of this Exemption.
     * 
     * @return string The remarks_private.
     */
    public function get_remarks_private()
    {
        return $this->get_default_property(self :: PROPERTY_REMARKS_PRIVATE);
    }

    /**
     * Sets the remarks_private of this Exemption.
     * 
     * @param string $remarks_private
     */
    public function set_remarks_private($remarks_private)
    {
        $this->set_default_property(self :: PROPERTY_REMARKS_PRIVATE, $remarks_private);
    }

    /**
     * Returns the motivation of this Exemption.
     * 
     * @return string The motivation.
     */
    public function get_motivation()
    {
        return $this->get_default_property(self :: PROPERTY_MOTIVATION);
    }

    /**
     * Sets the motivation of this Exemption.
     * 
     * @param string $motivation
     */
    public function set_motivation($motivation)
    {
        $this->set_default_property(self :: PROPERTY_MOTIVATION, $motivation);
    }

    /**
     * Returns the external_id of this Exemption.
     * 
     * @return integer The external_id.
     */
    public function get_external_id()
    {
        return $this->get_default_property(self :: PROPERTY_EXTERNAL_ID);
    }

    /**
     * Sets the external_id of this Exemption.
     * 
     * @param integer $external_id
     */
    public function set_external_id($external_id)
    {
        $this->set_default_property(self :: PROPERTY_EXTERNAL_ID, $external_id);
    }

    /**
     * Returns the external of this Exemption.
     * 
     * @return string The external.
     */
    public function get_external()
    {
        return $this->get_default_property(self :: PROPERTY_EXTERNAL);
    }

    /**
     * Sets the external of this Exemption.
     * 
     * @param string $external
     */
    public function set_external($external)
    {
        $this->set_default_property(self :: PROPERTY_EXTERNAL, $external);
    }

    /**
     *
     * @return string
     */
    public function get_state_string()
    {
        return self :: state_string($this->get_state());
    }

    /**
     *
     * @return string
     */
    public static function state_string($state)
    {
        switch ($state)
        {
            case self :: STATE_PENDING :
                return 'StatePending';
                break;
            case self :: STATE_ACCEPTED :
                return 'StateAccepted';
                break;
            case self :: STATE_REFUSED :
                return 'StateRefused';
                break;
        }
    }

    /**
     *
     * @param boolean $types_only
     * @return multitype:integer multitype:string
     */
    public static function get_state_types($types_only = false)
    {
        $types = array();
        
        $types[self :: STATE_PENDING] = self :: state_string(self :: STATE_PENDING);
        $types[self :: STATE_ACCEPTED] = self :: state_string(self :: STATE_ACCEPTED);
        $types[self :: STATE_REFUSED] = self :: state_string(self :: STATE_REFUSED);
        
        return ($types_only ? array_keys($types) : $types);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public function get_remarks()
    {
        $html = array();
        if ($this->get_remarks_public())
        {
            $html[] = $this->get_remarks_public();
        }
        if ($this->get_remarks_private())
        {
            $html[] = '<span style="color:red; clear:both" >' . $this->get_remarks_private() . '</span>';
        }
        
        return implode('', $html);
    }

    public function get_name()
    {
        $html = array();
        $html[] = $this->get_programme_name();
        if ($this->get_external())
        {
            $html[] = '</br><span style="color:blue;" >' . $this->get_external() . '</span>';
        }
        
        return implode('', $html);
    }
}
