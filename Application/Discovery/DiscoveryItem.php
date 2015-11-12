<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Ehb\Application\Discovery\Storage\DataManager;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DiscoveryItem extends DataClass
{
    const PROPERTY_TITLE = 'title';

    private $instance;

    public function get_instance()
    {
        return $this->instance;
    }

    public function set_instance($instance)
    {
        $this->instance = $instance;
    }

    /**
     *
     * @param string $title
     */
    public function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    /**
     *
     * @return string
     */
    public function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TITLE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }
}
