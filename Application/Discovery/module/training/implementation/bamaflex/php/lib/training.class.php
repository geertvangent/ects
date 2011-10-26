<?php
namespace application\discovery\module\training\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class Training extends \application\discovery\module\training\Training
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_DOMAIN_ID = 'domain_id';
    const PROPERTY_DOMAIN = 'domain';
    const PROPERTY_GOALS = 'goals';
    const PROPERTY_TYPE_ID = 'type_id';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_BAMA_TYPE = 'bama_type';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    
    const BAMA_TYPE_OTHER = 0;
    const BAMA_TYPE_BACHELOR = 1;
    const BAMA_TYPE_MASTER = 2;
    const BAMA_TYPE_CONTINUED = 3;
    const BAMA_TYPE_OLD = 4;

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

    function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    function get_domain_id()
    {
        return $this->get_default_property(self :: PROPERTY_DOMAIN_ID);
    }

    function set_domain_id($domain_id)
    {
        $this->set_default_property(self :: PROPERTY_DOMAIN_ID, $domain_id);
    }

    function get_domain()
    {
        return $this->get_default_property(self :: PROPERTY_DOMAIN);
    }

    function set_domain($domain)
    {
        $this->set_default_property(self :: PROPERTY_DOMAIN, $domain);
    }

    function get_goals()
    {
        return $this->get_default_property(self :: PROPERTY_GOALS);
    }

    function set_goals($goals)
    {
        $this->set_default_property(self :: PROPERTY_GOALS, $goals);
    }

    function get_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE_ID);
    }

    function set_type_id($type_id)
    {
        $this->set_default_property(self :: PROPERTY_TYPE_ID, $type_id);
    }

    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    function get_bama_type()
    {
        return $this->get_default_property(self :: PROPERTY_BAMA_TYPE);
    }

    function set_bama_type($bama_type)
    {
        $this->set_default_property(self :: PROPERTY_BAMA_TYPE, $bama_type);
    }

    function get_bama_type_string()
    {
        return self :: bama_type_string($this->get_bama_type());
    }

    /**
     * @return string
     */
    static function bama_type_string($bama_type)
    {
        switch ($bama_type)
        {
            case self :: BAMA_TYPE_OTHER :
                return 'Other';
                break;
            case self :: BAMA_TYPE_BACHELOR :
                return 'Bachelor';
                break;
            case self :: BAMA_TYPE_MASTER :
                return 'Master';
                break;
            case self :: BAMA_TYPE_CONTINUED :
                return 'Continued';
                break;
            case self :: BAMA_TYPE_OLD :
                return 'Old';
                break;
        }
    }

    function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    //    function get_deans()
    //    {
    //        return $this->deans;
    //    }
    //
    //    function set_deans($deans)
    //    {
    //        $this->deans = $deans;
    //    }
    //
    //    function has_deans()
    //    {
    //        return count($this->deans) > 0;
    //    }
    //
    //    function add_dean($dean)
    //    {
    //        $this->deans[] = $dean;
    //    }
    //
    //    function get_deans_string()
    //    {
    //        $deans = array();
    //        foreach ($this->get_deans() as $dean)
    //        {
    //            $deans[] = $dean->get_person();
    //        }
    //        return implode(', ', $deans);
    //    }
    

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_DOMAIN_ID;
        $extended_property_names[] = self :: PROPERTY_DOMAIN;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_BAMA_TYPE;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        
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