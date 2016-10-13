<?php
namespace Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass;

use Chamilo\Core\Tracking\Storage\DataClass\Tracker;

/**
 *
 * @package Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class View extends Tracker
{
    // Properties
    const PROPERTY_SESSION_ID = 'session_id';
    const PROPERTY_DATE = 'date';
    const PROPERTY_ENTITY_TYPE = 'entity_type';
    const PROPERTY_ENTITY_ID = 'entity_id';

    // Types
    const TYPE_TRAINING = 1;
    const TYPE_TRAJECTORY = 2;
    const TYPE_COURSE = 3;

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_SESSION_ID;
        $extended_property_names[] = self::PROPERTY_DATE;
        $extended_property_names[] = self::PROPERTY_ENTITY_TYPE;
        $extended_property_names[] = self::PROPERTY_ENTITY_ID;

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
     * @param string $session_id
     */
    public function setSessionId($session_id)
    {
        $this->set_default_property(self::PROPERTY_SESSION_ID, $session_id);
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
     * @param string $date
     */
    public function setDate($date)
    {
        $this->set_default_property(self::PROPERTY_DATE, $date);
    }

    /**
     *
     * @return integer
     */
    public function getEntityType()
    {
        return $this->get_default_property(self::PROPERTY_ENTITY_TYPE);
    }

    /**
     *
     * @param string $entity_type
     */
    public function setEntityType($entity_type)
    {
        $this->set_default_property(self::PROPERTY_ENTITY_TYPE, $entity_type);
    }

    /**
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->get_default_property(self::PROPERTY_ENTITY_ID);
    }

    /**
     *
     * @param string $entity_id
     */
    public function setEntityId($entity_id)
    {
        $this->set_default_property(self::PROPERTY_ENTITY_ID, $entity_id);
    }

    /**
     *
     * @param string[] $parameters
     */
    public function validate_parameters(array $parameters = array())
    {
        $this->setSessionId($parameters[self::PROPERTY_SESSION_ID]);
        $this->setDate($parameters[self::PROPERTY_DATE]);
        $this->setEntityType($parameters[self::PROPERTY_ENTITY_TYPE]);
        $this->setEntityId($parameters[self::PROPERTY_ENTITY_ID]);
    }
}