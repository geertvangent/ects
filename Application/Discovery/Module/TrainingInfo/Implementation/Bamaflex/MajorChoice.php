<?php
namespace Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex;

use Ehb\Application\Discovery\DiscoveryItem;

class MajorChoice extends DiscoveryItem
{
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_MAJOR_ID = 'major_id';
    const PROPERTY_NAME = 'name';

    /**
     *
     * @return int
     */
    public function get_source()
    {
        return $this->get_default_property(self::PROPERTY_SOURCE);
    }

    /**
     *
     * @param int $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self::PROPERTY_SOURCE, $source);
    }

    public function get_major_id()
    {
        return $this->get_default_property(self::PROPERTY_MAJOR_ID);
    }

    public function set_major_id($major_id)
    {
        $this->set_default_property(self::PROPERTY_MAJOR_ID, $major_id);
    }

    public function get_name()
    {
        return $this->get_default_property(self::PROPERTY_NAME);
    }

    public function set_name($name)
    {
        $this->set_default_property(self::PROPERTY_NAME, $name);
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_SOURCE;
        $extended_property_names[] = self::PROPERTY_MAJOR_ID;
        $extended_property_names[] = self::PROPERTY_NAME;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }
}
