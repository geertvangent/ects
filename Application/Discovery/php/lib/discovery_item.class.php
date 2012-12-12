<?php
namespace application\discovery;

use common\libraries\DataClass;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DiscoveryItem extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_TITLE = 'title';

    private $instance;

    function get_instance()
    {
        return $this->instance;
    }

    function set_instance($instance)
    {
        $this->instance = $instance;
    }

    /**
     *
     * @param string $title
     */
    function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    /**
     *
     * @return string
     */
    function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TITLE;
        
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
}
?>