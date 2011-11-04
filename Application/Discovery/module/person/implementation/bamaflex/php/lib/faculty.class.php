<?php
namespace application\discovery\module\person\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class Faculty extends \application\discovery\module\person\Faculty
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    
    private $deans;

    /**
     * @return int
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * @param int $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }
    
    function get_deans()
    {
    	return $this->deans;
    }
    
    function set_deans($deans)
    {
    	$this->deans = $deans;
    }
    
    function has_deans()
    {
    	return count($this->deans) > 0;
    }
    
    function add_dean($dean)
    {
    	$this->deans[] = $dean;
    }
    
    function get_deans_string()
    {
    	$deans = array();
    	foreach($this->get_deans() as $dean)
    	{
    		$deans[] = $dean->get_person();
    	}
    	return implode(', ', $deans);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }
}
?>