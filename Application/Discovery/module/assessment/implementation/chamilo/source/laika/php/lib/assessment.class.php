<?php
namespace application\discovery\module\assessment\implementation\chamilo\source\laika;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.assessment.implementation.chamilo.source.laika.discovery
 * @author Hans De Bisschop
 */
class Assessment extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Assessment properties
     */
    const PROPERTY_CLUSTER = 'cluster';
    const PROPERTY_SCALE = 'scale';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_CLUSTER;
        $extended_property_names[] = self :: PROPERTY_SCALE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the cluster of this Assessment.
     * @return string The cluster.
     */
    function get_cluster()
    {
        return $this->get_default_property(self :: PROPERTY_CLUSTER);
    }

    /**
     * Sets the cluster of this Assessment.
     * @param string $cluster
     */
    function set_cluster($cluster)
    {
        $this->set_default_property(self :: PROPERTY_CLUSTER, $cluster);
    }

    /**
     * Returns the scale of this Assessment.
     * @return string The scale.
     */
    function get_scale()
    {
        return $this->get_default_property(self :: PROPERTY_SCALE);
    }

    /**
     * Sets the scale of this Assessment.
     * @param string $scale
     */
    function set_scale($scale)
    {
        $this->set_default_property(self :: PROPERTY_SCALE, $scale);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
