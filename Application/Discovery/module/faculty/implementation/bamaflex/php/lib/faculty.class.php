<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use application\discovery\module\faculty\DataManager;

use application\discovery\DiscoveryDataManager;

class Faculty extends \application\discovery\module\faculty\Faculty
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_NEXT_ID = 'next_id';
    const PROPERTY_PREVIOUS_SOURCE = 'previous_source';
    const PROPERTY_NEXT_SOURCE = 'next_source';
    
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

    function get_previous($module_instance, $recursive = true)
    {
        $faculties = array();
        $faculty = $this;
        if ($this->get_previous_id())
        {
            do
            {
                $parameters = new Parameters();
                $parameters->set_faculty_id($faculty->get_previous_id());
                $parameters->set_source($faculty->get_previous_source());
                $faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($parameters);
                $faculties[] = $faculty;
            }
            while ($faculty instanceof Faculty && $faculty->get_previous_id() && $recursive);
        }
        return $faculties;
    }

    function get_next($module_instance, $recursive = true)
    {
        $faculties = array();
        $faculty = $this;
        if ($this->get_next_id())
        {
            do
            {
                $parameters = new Parameters();
                $parameters->set_faculty_id($faculty->get_next_id());
                $parameters->set_source($faculty->get_next_source());
                
                $faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($parameters);
                $faculties[] = $faculty;
            }
            while ($faculty instanceof Faculty && $faculty->get_next_id() && $recursive);
        }
        return $faculties;
    }

    function get_all($module_instance)
    {
        $faculties = $this->get_next($module_instance);
        array_unshift($faculties, $this);
        
        foreach ($this->get_previous($module_instance) as $faculty)
        {
            array_unshift($faculties, $faculty);
        }
        
        return $faculties;
    }

    function get_previous_id()
    {
        return $this->get_default_property(self :: PROPERTY_PREVIOUS_ID);
    }

    function set_previous_id($previous_id)
    {
        $this->set_default_property(self :: PROPERTY_PREVIOUS_ID, $previous_id);
    }

    function get_next_id()
    {
        return $this->get_default_property(self :: PROPERTY_NEXT_ID);
    }

    function set_next_id($next_id)
    {
        $this->set_default_property(self :: PROPERTY_NEXT_ID, $next_id);
    }

    function get_previous_source()
    {
        return $this->get_default_property(self :: PROPERTY_PREVIOUS_SOURCE);
    }

    function set_previous_source($previous_source)
    {
        $this->set_default_property(self :: PROPERTY_PREVIOUS_SOURCE, $previous_source);
    }

    function get_next_source()
    {
        return $this->get_default_property(self :: PROPERTY_NEXT_SOURCE);
    }

    function set_next_source($next_source)
    {
        $this->set_default_property(self :: PROPERTY_NEXT_SOURCE, $next_source);
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
        foreach ($this->get_deans() as $dean)
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
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_ID;
        $extended_property_names[] = self :: PROPERTY_NEXT_ID;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_SOURCE;
        $extended_property_names[] = self :: PROPERTY_NEXT_SOURCE;
        
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