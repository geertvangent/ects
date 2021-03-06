<?php
namespace Ehb\Application\Discovery\DataSource\Bamaflex\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Ehb\Application\Discovery\DataSource\Bamaflex\Storage\DataManager;

/**
 * application.discovery.connection.bamaflex.
 * 
 * @author GillardMagali
 */
class HistoryReference extends DataClass
{
    
    /**
     * HistoryReference properties
     */
    const PROPERTY_SOURCE = 'source';

    /**
     * Get the default properties
     * 
     * @param $extended_property_names multitype:string
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_SOURCE;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager::getInstance();
    }

    /**
     * Returns the source of this History.
     * 
     * @return int The source.
     */
    public function get_source()
    {
        return $this->get_default_property(self::PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this History.
     * 
     * @param $source int
     */
    public function set_source($source)
    {
        $this->set_default_property(self::PROPERTY_SOURCE, $source);
    }
}
