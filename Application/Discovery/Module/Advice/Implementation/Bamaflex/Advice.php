<?php
namespace Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex;

use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\DiscoveryItem;

/**
 * application.discovery.module.advice.implementation.bamaflex
 *
 * @author Magali Gillard
 */
class Advice extends DiscoveryItem
{

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
    const PROPERTY_PERSON_ID = 'person_id';
    /**
     *
     * @var string
     */
    const PROPERTY_MOTIVATION = 'motivation';
    /**
     *
     * @var string
     */
    const PROPERTY_OMBUDSMAN = 'ombudsman';
    /**
     *
     * @var string
     */
    const PROPERTY_VOTE = 'vote';
    /**
     *
     * @var integer
     */
    const PROPERTY_MEASURES_VISIBLE = 'measures_visible';
    /**
     *
     * @var string
     */
    const PROPERTY_MEASURES = 'measures';
    /**
     *
     * @var integer
     */
    const PROPERTY_ADVICE_VISIBLE = 'advice_visible';
    /**
     *
     * @var string
     */
    const PROPERTY_ADVICE = 'advice';
    /**
     *
     * @var integer
     */
    const PROPERTY_MEASURES_VALID = 'measures_valid';

    /**
     *
     * @var string
     */
    const PROPERTY_DATE = 'date';
    /**
     *
     * @var integer
     */
    const PROPERTY_TRY = 'try';
    /**
     *
     * @var integer
     */
    const PROPERTY_DECISION_TYPE_ID = 'decision_type_id';
    /**
     *
     * @var string
     */
    const PROPERTY_DECISION_TYPE = 'decision_type';
    const TYPE_MOTIVATION = 1;
    const TYPE_OMBUDSMAN = 2;
    const TYPE_VOTE = 3;
    const TYPE_MEASURES_INVALID = 4;
    const TYPE_ADVICE = 5;
    const TYPE_MEASURES_VALID = 6;

    public static function type_string($type)
    {
        switch ($type)
        {
            case self :: TYPE_MOTIVATION :
                return 'TypeMotivation';
                break;
            case self :: TYPE_OMBUDSMAN :
                return 'TypeOmbudsman';
                break;
            case self :: TYPE_VOTE :
                return 'TypeVote';
                break;
            case self :: TYPE_MEASURES_INVALID :
                return 'TypeMeasuresInvalid';
                break;
            case self :: TYPE_ADVICE :
                return 'TypeAdvice';
                break;
            case self :: TYPE_MEASURES_VALID :
                return 'TypeMeasuresValid';
                break;
        }
    }

    /**
     *
     * @param boolean $types_only
     * @return multitype:integer multitype:string
     */
    public static function get_type_types($types_only = false)
    {
        $types = array();

        $types[self :: TYPE_MOTIVATION] = self :: type_string(self :: TYPE_MOTIVATION);
        $types[self :: TYPE_OMBUDSMAN] = self :: type_string(self :: TYPE_OMBUDSMAN);
        $types[self :: TYPE_VOTE] = self :: type_string(self :: TYPE_VOTE);
        $types[self :: TYPE_MEASURES_INVALID] = self :: type_string(self :: TYPE_MEASURES_INVALID);
        $types[self :: TYPE_ADVICE] = self :: type_string(self :: TYPE_ADVICE);
        $types[self :: TYPE_MEASURES_VALID] = self :: type_string(self :: TYPE_MEASURES_VALID);

        return ($types_only ? array_keys($types) : $types);
    }

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
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_MOTIVATION;
        $extended_property_names[] = self :: PROPERTY_OMBUDSMAN;
        $extended_property_names[] = self :: PROPERTY_VOTE;
        $extended_property_names[] = self :: PROPERTY_MEASURES_VISIBLE;
        $extended_property_names[] = self :: PROPERTY_MEASURES;
        $extended_property_names[] = self :: PROPERTY_ADVICE_VISIBLE;
        $extended_property_names[] = self :: PROPERTY_ADVICE;
        $extended_property_names[] = self :: PROPERTY_MEASURES_VALID;
        $extended_property_names[] = self :: PROPERTY_DATE;
        $extended_property_names[] = self :: PROPERTY_TRY;
        $extended_property_names[] = self :: PROPERTY_DECISION_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_DECISION_TYPE;

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
     * Returns the enrollment_id of this Advice.
     *
     * @return integer The enrollment_id.
     */
    public function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment_id of this Advice.
     *
     * @param integer $enrollment_id
     */
    public function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the year of this Advice.
     *
     * @return string The year.
     */
    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this Advice.
     *
     * @param string $year
     */
    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the person_id of this Advice.
     *
     * @return integer The person_id.
     */
    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this Advice.
     *
     * @param integer $person_id
     */
    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the motivation of this Advice.
     *
     * @return string The motivation.
     */
    public function get_motivation()
    {
        return $this->get_default_property(self :: PROPERTY_MOTIVATION);
    }

    /**
     * Sets the motivation of this Advice.
     *
     * @param string $motivation
     */
    public function set_motivation($motivation)
    {
        $this->set_default_property(self :: PROPERTY_MOTIVATION, $motivation);
    }

    /**
     * Returns the ombudsman of this Advice.
     *
     * @return string The ombudsman.
     */
    public function get_ombudsman()
    {
        return $this->get_default_property(self :: PROPERTY_OMBUDSMAN);
    }

    /**
     * Sets the ombudsman of this Advice.
     *
     * @param string $ombudsman
     */
    public function set_ombudsman($ombudsman)
    {
        $this->set_default_property(self :: PROPERTY_OMBUDSMAN, $ombudsman);
    }

    /**
     * Returns the vote of this Advice.
     *
     * @return string The vote.
     */
    public function get_vote()
    {
        return $this->get_default_property(self :: PROPERTY_VOTE);
    }

    /**
     * Sets the vote of this Advice.
     *
     * @param string $vote
     */
    public function set_vote($vote)
    {
        $this->set_default_property(self :: PROPERTY_VOTE, $vote);
    }

    /**
     * Returns the measures_visible of this Advice.
     *
     * @return integer The measures_visible.
     */
    public function get_measures_visible()
    {
        return $this->get_default_property(self :: PROPERTY_MEASURES_VISIBLE);
    }

    /**
     * Sets the measures_visible of this Advice.
     *
     * @param integer $measures_visible
     */
    public function set_measures_visible($measures_visible)
    {
        $this->set_default_property(self :: PROPERTY_MEASURES_VISIBLE, $measures_visible);
    }

    /**
     * Returns the measures of this Advice.
     *
     * @return string The measures.
     */
    public function get_measures()
    {
        return $this->get_default_property(self :: PROPERTY_MEASURES);
    }

    /**
     * Sets the measures of this Advice.
     *
     * @param string $measures
     */
    public function set_measures($measures)
    {
        $this->set_default_property(self :: PROPERTY_MEASURES, $measures);
    }

    /**
     * Returns the advice_visible of this Advice.
     *
     * @return integer The advice_visible.
     */
    public function get_advice_visible()
    {
        return $this->get_default_property(self :: PROPERTY_ADVICE_VISIBLE);
    }

    /**
     * Sets the advice_visible of this Advice.
     *
     * @param integer $advice_visible
     */
    public function set_advice_visible($advice_visible)
    {
        $this->set_default_property(self :: PROPERTY_ADVICE_VISIBLE, $advice_visible);
    }

    /**
     * Returns the advice of this Advice.
     *
     * @return string The advice.
     */
    public function get_advice()
    {
        return $this->get_default_property(self :: PROPERTY_ADVICE);
    }

    /**
     * Sets the advice of this Advice.
     *
     * @param string $advice
     */
    public function set_advice($advice)
    {
        $this->set_default_property(self :: PROPERTY_ADVICE, $advice);
    }

    /**
     * Returns the measures_valid of this Advice.
     *
     * @return integer The measures_valid.
     */
    public function get_measures_valid()
    {
        return $this->get_default_property(self :: PROPERTY_MEASURES_VALID);
    }

    /**
     * Sets the measures_valid of this Advice.
     *
     * @param integer $measures_valid
     */
    public function set_measures_valid($measures_valid)
    {
        $this->set_default_property(self :: PROPERTY_MEASURES_VALID, $measures_valid);
    }

    /**
     * Returns the date of this Advice.
     *
     * @return string The date.
     */
    public function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     * Sets the date of this Advice.
     *
     * @param string $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     * Returns the try of this Advice.
     *
     * @return integer The try.
     */
    public function get_try()
    {
        return $this->get_default_property(self :: PROPERTY_TRY);
    }

    /**
     * Sets the try of this Advice.
     *
     * @param integer $try
     */
    public function set_try($try)
    {
        $this->set_default_property(self :: PROPERTY_TRY, $try);
    }

    /**
     * Returns the decision_type_id of this Advice.
     *
     * @return integer The decision_type_id.
     */
    public function get_decision_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_DECISION_TYPE_ID);
    }

    /**
     * Sets the decision_type_id of this Advice.
     *
     * @param integer $decision_type_id
     */
    public function set_decision_type_id($decision_type_id)
    {
        $this->set_default_property(self :: PROPERTY_DECISION_TYPE_ID, $decision_type_id);
    }

    /**
     * Returns the decision_type of this Advice.
     *
     * @return string The decision_type.
     */
    public function get_decision_type()
    {
        return $this->get_default_property(self :: PROPERTY_DECISION_TYPE);
    }

    /**
     * Sets the decision_type of this Advice.
     *
     * @param string $decision_type
     */
    public function set_decision_type($decision_type)
    {
        $this->set_default_property(self :: PROPERTY_DECISION_TYPE, $decision_type);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: class_name(), true);
    }
}
