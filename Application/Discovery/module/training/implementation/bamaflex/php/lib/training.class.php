<?php
namespace application\discovery\module\training\implementation\bamaflex;

use application\discovery\module\training\DataManager;

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
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_NEXT_ID = 'next_id';
    
    const BAMA_TYPE_OTHER = 0;
    const BAMA_TYPE_BACHELOR = 1;
    const BAMA_TYPE_MASTER = 2;
    const BAMA_TYPE_CONTINUED = 3;
    const BAMA_TYPE_OLD = 4;
    
    private $majors;
    private $languages;
    private $packages;
    private $choices;
    private $choice_options;
    private $trajectories;

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
    static 

    function bama_type_string($bama_type)
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

    function get_start_date()
    {
        return $this->get_default_property(self :: PROPERTY_START_DATE);
    }

    function set_start_date($start_date)
    {
        $this->set_default_property(self :: PROPERTY_START_DATE, $start_date);
    }

    function get_end_date()
    {
        return $this->get_default_property(self :: PROPERTY_END_DATE);
    }

    function set_end_date($end_date)
    {
        $this->set_default_property(self :: PROPERTY_END_DATE, $end_date);
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

    function get_previous($module_instance, $recursive = true)
    {
        $trainings = array();
        $training = $this;
        if ($this->get_previous_id())
        {
            do
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($training->get_previous_id(), $training->get_source());
                
                $training = DataManager :: get_instance($module_instance)->retrieve_training($parameters);
                $trainings[] = $training;
            }
            while ($training instanceof Training && $training->get_previous_id() && $recursive);
        }
        return $trainings;
    }

    function get_next($module_instance, $recursive = true)
    {
        $trainings = array();
        $training = $this;
        if ($this->get_next_id())
        {
            do
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($training->get_next_id(), $training->get_source());
                
                $training = DataManager :: get_instance($module_instance)->retrieve_training($parameters);
                $trainings[] = $training;
            }
            while ($training instanceof Training && $training->get_next_id() && $recursive);
        }
        return $trainings;
    }

    function get_all($module_instance)
    {
        $trainings = $this->get_next($module_instance);
        array_unshift($trainings, $this);
        
        foreach ($this->get_previous($module_instance) as $training)
        {
            array_unshift($trainings, $training);
        }
        return $trainings;
    }

    function get_majors()
    {
        return $this->majors;
    }

    function set_majors($majors)
    {
        $this->majors = $majors;
    }

    function has_majors()
    {
        return count($this->majors) > 0;
    }

    function add_major($major)
    {
        $this->majors[] = $major;
    }

    function get_languages()
    {
        return $this->languages;
    }

    function get_languages_string()
    {
        $languages = array();
        foreach ($this->get_languages() as $language)
        {
            $languages[] = $language->get_name();
        }
        return implode(',', $languages);
    }

    function set_languages($languages)
    {
        $this->languages = $languages;
    }

    function has_languages()
    {
        return count($this->languages) > 0;
    }

    function add_language($language)
    {
        $this->languages[] = $language;
    }

    function get_packages()
    {
        return $this->packages;
    }

    function set_packages($packages)
    {
        $this->packages = $packages;
    }

    function has_packages()
    {
        return count($this->packages) > 0;
    }

    function add_package($package)
    {
        $this->packages[] = $package;
    }

    function get_choices()
    {
        return $this->choices;
    }

    function set_choices($choices)
    {
        $this->choices = $choices;
    }

    function has_choices()
    {
        return count($this->choices) > 0;
    }

    function add_choice($choice)
    {
        $this->choices[] = $choice;
    }

    function get_choice_options()
    {
        return $this->choice_options;
    }

    function set_choice_options($choice_options)
    {
        $this->choice_options = $choice_options;
    }

    function has_choice_options()
    {
        return count($this->choice_options) > 0;
    }

    function add_choice_option($choice_option)
    {
        $this->choice_options[] = $choice_option;
    }

    function get_trajectories()
    {
        return $this->trajectories;
    }

    function set_trajectories($trajectories)
    {
        $this->trajectories = $trajectories;
    }

    function has_trajectories()
    {
        return count($this->trajectories) > 0;
    }

    function add_trajectory($trajectory)
    {
        $this->trajectories[] = $trajectory;
    }

    function has_options()
    {
        return $this->has_choice_options() || $this->has_choices() || $this->has_majors() || $this->has_packages();
    }

    function has_major_choices()
    {
        foreach ($this->get_majors() as $major)
        {
            if ($major->has_choices())
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static 

    function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_DOMAIN_ID;
        $extended_property_names[] = self :: PROPERTY_DOMAIN;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_BAMA_TYPE;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_START_DATE;
        $extended_property_names[] = self :: PROPERTY_END_DATE;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_ID;
        
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