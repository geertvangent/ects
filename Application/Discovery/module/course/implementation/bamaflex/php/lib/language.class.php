<?php
namespace application\discovery\module\course\implementation\bamaflex;


use application\discovery\DiscoveryItem;

class Language extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_LANGUAGE_ID = 'language_id';
    const PROPERTY_LANGUAGE = 'language';

    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    function get_language_id()
    {
        return $this->get_default_property(self :: PROPERTY_LANGUAGE_ID);
    }

    function set_language_id($language_id)
    {
        $this->set_default_property(self :: PROPERTY_LANGUAGE_ID, $language_id);
    }

    function get_language()
    {
        return $this->get_default_property(self :: PROPERTY_LANGUAGE);
    }

    function set_language($language)
    {
        $this->set_default_property(self :: PROPERTY_LANGUAGE, $language);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_ID;
        $extended_property_names[] = self :: PROPERTY_LANGUAGE_ID;
        $extended_property_names[] = self :: PROPERTY_LANGUAGE;

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
        return $this->get_language();
    }
}
