<?php
namespace Chamilo\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Chamilo\Application\Discovery\DiscoveryItem;

class Cas extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_APPLICATION_ID = 'application_id';
    const PROPERTY_ACTION_ID = 'action_id';
    const PROPERTY_DATE = 'date';

    /**
     *
     * @return int
     */
    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     *
     * @param int $person_id
     */
    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     *
     * @return int
     */
    public function get_application_id()
    {
        return $this->get_default_property(self :: PROPERTY_APPLICATION_ID);
    }

    /**
     *
     * @param int $application_id
     */
    public function set_application_id($application_id)
    {
        $this->set_default_property(self :: PROPERTY_APPLICATION_ID, $application_id);
    }

    /**
     *
     * @return int
     */
    public function get_action_id()
    {
        return $this->get_default_property(self :: PROPERTY_ACTION_ID);
    }

    /**
     *
     * @param int $action_id
     */
    public function set_action_id($action_id)
    {
        $this->set_default_property(self :: PROPERTY_ACTION_ID, $action_id);
    }

    /**
     *
     * @return timestamp
     */
    public function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     *
     * @param timestamp $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_APPLICATION_ID;
        $extended_property_names[] = self :: PROPERTY_ACTION_ID;
        $extended_property_names[] = self :: PROPERTY_DATE;
        
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
}
