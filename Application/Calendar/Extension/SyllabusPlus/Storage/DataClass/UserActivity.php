<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class UserActivity extends Activity
{
    const PROPERTY_PERSON_ID = 'person_id';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_PERSON_ID;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return integer
     */
    public function getPersonId()
    {
        return $this->get_default_property(self::PROPERTY_PERSON_ID);
    }

    /**
     *
     * @param integer $personId
     */
    public function setPersonId($personId)
    {
        $this->set_default_property(self::PROPERTY_PERSON_ID, $personId);
    }
}