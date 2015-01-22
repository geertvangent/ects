<?php
namespace Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Chamilo\Application\Discovery\DiscoveryItem;

class Teacher extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_COORDINATOR = 'coordinator';
    const TYPE_TEACHER = 0;
    const TYPE_COORDINATOR = 1;

    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    public function get_coordinator()
    {
        return $this->get_default_property(self :: PROPERTY_COORDINATOR);
    }

    public function set_coordinator($coordinator)
    {
        $this->set_default_property(self :: PROPERTY_COORDINATOR, $coordinator);
    }

    public function is_coordinator()
    {
        return $this->get_coordinator() == 1;
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
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
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user_by_official_code(
            $this->get_person_id());
        
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
