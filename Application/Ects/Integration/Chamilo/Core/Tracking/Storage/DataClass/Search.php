<?php
namespace Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass;

use Chamilo\Core\Tracking\Storage\DataClass\Tracker;

/**
 *
 * @package Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Search extends Tracker
{
    // Properties
    const PROPERTY_SESSION_ID = 'session_id';
    const PROPERTY_DATE = 'date';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_TEXT = 'text';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_SESSION_ID;
        $extended_property_names[] = self::PROPERTY_DATE;
        $extended_property_names[] = self::PROPERTY_YEAR;
        $extended_property_names[] = self::PROPERTY_FACULTY;
        $extended_property_names[] = self::PROPERTY_TYPE;
        $extended_property_names[] = self::PROPERTY_TEXT;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->get_default_property(self::PROPERTY_SESSION_ID);
    }

    /**
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->set_default_property(self::PROPERTY_SESSION_ID, $sessionId);
    }

    /**
     *
     * @return integer
     */
    public function getDate()
    {
        return $this->get_default_property(self::PROPERTY_DATE);
    }

    /**
     *
     * @param integer $date
     */
    public function setDate($date)
    {
        $this->set_default_property(self::PROPERTY_DATE, $date);
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->get_default_property(self::PROPERTY_YEAR);
    }

    /**
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->set_default_property(self::PROPERTY_YEAR, $year);
    }

    /**
     *
     * @return integer
     */
    public function getFaculty()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY);
    }

    /**
     *
     * @param string $faculty
     */
    public function setFaculty($faculty)
    {
        $this->set_default_property(self::PROPERTY_FACULTY, $faculty);
    }

    /**
     *
     * @return integer
     */
    public function getType()
    {
        return $this->get_default_property(self::PROPERTY_TYPE);
    }

    /**
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->set_default_property(self::PROPERTY_TYPE, $type);
    }

    /**
     *
     * @return string
     */
    public function getText()
    {
        return $this->get_default_property(self::PROPERTY_TEXT);
    }

    /**
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->set_default_property(self::PROPERTY_TEXT, $text);
    }

    /**
     *
     * @param string[] $parameters
     */
    public function validate_parameters(array $parameters = array())
    {
        $this->setSessionId($parameters[self::PROPERTY_SESSION_ID]);
        $this->setDate($parameters[self::PROPERTY_DATE]);
        $this->setYear($parameters[self::PROPERTY_YEAR]);
        
        $facultyValue = $parameters[self::PROPERTY_FACULTY] ? (int) $parameters[self::PROPERTY_FACULTY] : null;
        $this->setFaculty($facultyValue);
        
        $typeValue = $parameters[self::PROPERTY_TYPE] ? (int) $parameters[self::PROPERTY_TYPE] : null;
        $this->setType($typeValue);
        
        $this->setText($parameters[self::PROPERTY_TEXT]);
    }
}