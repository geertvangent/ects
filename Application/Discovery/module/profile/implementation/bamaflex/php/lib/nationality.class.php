<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use common\libraries\Translation;
use application\discovery\DiscoveryDataManager;
use common\libraries\DataClass;

class Nationality extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_TYPE = 'type';
    const PROPERTY_NATIONALITY = 'nationality';
    const TYPE_PRIMARY = 1;
    const TYPE_SECONDARY = 2;

    /**
     *
     * @return int
     */
    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     *
     * @return string
     */
    function get_type_string()
    {
        switch ($this->get_type())
        {
            case self :: TYPE_PRIMARY :
                return 'Primary';
                break;
            case self :: TYPE_SECONDARY :
                return 'Secondary';
                break;
        }
    }

    /**
     *
     * @return string
     */
    function get_nationality()
    {
        return $this->get_default_property(self :: PROPERTY_NATIONALITY);
    }

    /**
     *
     * @param int $type
     */
    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @param string $nationality
     */
    function set_nationality($nationality)
    {
        $this->set_default_property(self :: PROPERTY_NATIONALITY, $nationality);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_NATIONALITY;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    function __toString()
    {
        return $this->get_nationality();
    }
}
?>