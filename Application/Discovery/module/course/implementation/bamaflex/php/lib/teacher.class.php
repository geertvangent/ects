<?php
namespace application\discovery\module\course\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\DiscoveryItem;

class Teacher extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_COORDINATOR = 'coordinator';
    const TYPE_TEACHER = 0;
    const TYPE_COORDINATOR = 1;

    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    function get_coordinator()
    {
        return $this->get_default_property(self :: PROPERTY_COORDINATOR);
    }

    function set_coordinator($coordinator)
    {
        $this->set_default_property(self :: PROPERTY_COORDINATOR, $coordinator);
    }

    function is_coordinator()
    {
        return $this->get_coordinator() == 1;
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_ID;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_COORDINATOR;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    function get_data_manager()
    {
//         return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        $user = UserDataManager :: get_instance()->retrieve_user_by_official_code($this->get_person_id());

        if ($user)
        {
            return $user->get_fullname();
        }
        else
        {
            return '-';
        }
    }
}
