<?php
namespace Chamilo\Application\Discovery\Module\Training\Implementation\Bamaflex;

use Chamilo\Application\Discovery\Module\Training\DataManager;
use Chamilo\Application\Discovery\DataSource\Bamaflex\HistoryReference;

class Training extends \Chamilo\Application\Discovery\Module\Training\Training
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
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_NEXT_ID = 'next_id';
    const BAMA_TYPE_OTHER = 0;
    const BAMA_TYPE_BACHELOR = 1;
    const BAMA_TYPE_MASTER = 2;
    const BAMA_TYPE_CONTINUED = 3;
    const BAMA_TYPE_OLD = 4;
    const REFERENCE_PREVIOUS = 1;
    const REFERENCE_NEXT = 2;

    private $majors;

    private $languages;

    private $packages;

    private $choices;

    private $choice_options;

    private $trajectories;

    private $groups;

    private $courses;

    /**
     *
     * @var multitype:HistoryReference
     */
    private $references;

    /**
     *
     * @return int
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     *
     * @param int $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    public function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    public function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    public function get_domain_id()
    {
        return $this->get_default_property(self :: PROPERTY_DOMAIN_ID);
    }

    public function set_domain_id($domain_id)
    {
        $this->set_default_property(self :: PROPERTY_DOMAIN_ID, $domain_id);
    }

    public function get_domain()
    {
        return $this->get_default_property(self :: PROPERTY_DOMAIN);
    }

    public function set_domain($domain)
    {
        $this->set_default_property(self :: PROPERTY_DOMAIN, $domain);
    }

    public function get_goals()
    {
        return $this->get_default_property(self :: PROPERTY_GOALS);
    }

    public function set_goals($goals)
    {
        $this->set_default_property(self :: PROPERTY_GOALS, $goals);
    }

    public function get_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE_ID);
    }

    public function set_type_id($type_id)
    {
        $this->set_default_property(self :: PROPERTY_TYPE_ID, $type_id);
    }

    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    public function get_bama_type()
    {
        return $this->get_default_property(self :: PROPERTY_BAMA_TYPE);
    }

    public function set_bama_type($bama_type)
    {
        $this->set_default_property(self :: PROPERTY_BAMA_TYPE, $bama_type);
    }

    public function get_bama_type_string()
    {
        return self :: bama_type_string($this->get_bama_type());
    }

    /**
     *
     * @return string
     */
    static public 

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

    public function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    public function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    public function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    public function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    public function get_start_date()
    {
        return $this->get_default_property(self :: PROPERTY_START_DATE);
    }

    public function set_start_date($start_date)
    {
        $this->set_default_property(self :: PROPERTY_START_DATE, $start_date);
    }

    public function get_end_date()
    {
        return $this->get_default_property(self :: PROPERTY_END_DATE);
    }

    public function set_end_date($end_date)
    {
        $this->set_default_property(self :: PROPERTY_END_DATE, $end_date);
    }

    public function get_majors()
    {
        return $this->majors;
    }

    public function set_majors($majors)
    {
        $this->majors = $majors;
    }

    public function has_majors()
    {
        return count($this->majors) > 0;
    }

    public function add_major($major)
    {
        $this->majors[] = $major;
    }

    public function get_languages()
    {
        return $this->languages;
    }

    public function get_languages_string()
    {
        $languages = array();
        foreach ($this->get_languages() as $language)
        {
            $languages[] = $language->get_name();
        }
        return implode(',', $languages);
    }

    public function set_languages($languages)
    {
        $this->languages = $languages;
    }

    public function has_languages()
    {
        return count($this->languages) > 0;
    }

    public function add_language($language)
    {
        $this->languages[] = $language;
    }

    public function get_packages()
    {
        return $this->packages;
    }

    public function set_packages($packages)
    {
        $this->packages = $packages;
    }

    public function has_packages()
    {
        return count($this->packages) > 0;
    }

    public function add_package($package)
    {
        $this->packages[] = $package;
    }

    public function get_courses()
    {
        return $this->courses;
    }

    public function set_courses($courses)
    {
        $this->courses = $courses;
    }

    public function has_courses()
    {
        return count($this->courses) > 0;
    }

    public function add_course($course)
    {
        $this->courses[] = $course;
    }

    public function get_choices()
    {
        return $this->choices;
    }

    public function set_choices($choices)
    {
        $this->choices = $choices;
    }

    public function has_choices()
    {
        return count($this->choices) > 0;
    }

    public function add_choice($choice)
    {
        $this->choices[] = $choice;
    }

    public function get_choice_options()
    {
        return $this->choice_options;
    }

    public function set_choice_options($choice_options)
    {
        $this->choice_options = $choice_options;
    }

    public function has_choice_options()
    {
        return count($this->choice_options) > 0;
    }

    public function add_choice_option($choice_option)
    {
        $this->choice_options[] = $choice_option;
    }

    public function get_trajectories()
    {
        return $this->trajectories;
    }

    public function set_trajectories($trajectories)
    {
        $this->trajectories = $trajectories;
    }

    public function has_trajectories()
    {
        return count($this->trajectories) > 0;
    }

    public function add_trajectory($trajectory)
    {
        $this->trajectories[] = $trajectory;
    }

    public function has_options()
    {
        return $this->has_choice_options() || $this->has_choices() || $this->has_majors() || $this->has_packages();
    }

    public function has_major_choices()
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

    public function get_groups()
    {
        return $this->groups;
    }

    public function set_groups($groups)
    {
        $this->groups = $groups;
    }

    public function has_groups()
    {
        return count($this->groups) > 0;
    }

    public function add_group($group)
    {
        $this->groups[] = $group;
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static public 

    function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_DOMAIN_ID;
        $extended_property_names[] = self :: PROPERTY_DOMAIN;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP;
        $extended_property_names[] = self :: PROPERTY_GROUP_ID;
        $extended_property_names[] = self :: PROPERTY_BAMA_TYPE;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_START_DATE;
        $extended_property_names[] = self :: PROPERTY_END_DATE;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_ID;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    public function is_current()
    {
        $current_date = time();
        $start_date = strtotime($this->get_start_date());
        $end_date = strtotime($this->get_end_date());
        
        if ($current_date >= $start_date && $current_date <= $end_date)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     *
     * @param $module_instance Instance
     * @param $recursive boolean
     * @return multitype:string:Training
     */
    public function get_previous($module_instance, $recursive = true)
    {
        $trainings = array();
        if ($this->has_previous_references())
        {
            foreach ($this->get_previous_references() as $previous_reference)
            {
                $parameters = new \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters();
                $parameters->set_training_id($previous_reference->get_id());
                $parameters->set_source($previous_reference->get_source());
                
                $training = DataManager :: get_instance($module_instance)->retrieve_training($parameters);
                if ($training instanceof Training)
                {
                    $trainings[$training->get_year()][] = $training;
                }
                
                if ($training->has_next_references(true) && $this->has_previous_references(true) && $recursive)
                {
                    $trainings = array_merge_recursive($trainings, $training->get_previous($module_instance));
                }
            }
        }
        return $trainings;
    }

    /**
     *
     * @param $module_instance Instance
     * @param $recursive boolean
     * @return multitype:string:Training
     */
    public function get_next($module_instance, $recursive = true)
    {
        $trainings = array();
        
        if ($this->has_next_references())
        {
            foreach ($this->get_next_references() as $next_reference)
            {
                $parameters = new \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters();
                $parameters->set_training_id($next_reference->get_id());
                $parameters->set_source($next_reference->get_source());
                
                $training = DataManager :: get_instance($module_instance)->retrieve_training($parameters);
                
                if ($training instanceof Training)
                {
                    $trainings[$training->get_year()][] = $training;
                }
                
                if ($training->has_previous_references(true) && $this->has_next_references(true) && $recursive)
                {
                    $trainings = array_merge_recursive($trainings, $training->get_next($module_instance));
                }
            }
        }
        return $trainings;
    }

    /**
     *
     * @param $module_instance Instance
     * @return multitype:string:Faculty
     */
    public function get_all($module_instance)
    {
        $trainings = $this->get_next($module_instance);
        
        $trainings[$this->get_year()][] = $this;
        $trainings = array_merge_recursive($trainings, $this->get_previous($module_instance));
        
        ksort($trainings);
        
        return $trainings;
    }

    /**
     *
     * @return multitype:HistoryReference
     */
    public function get_previous_references()
    {
        return $this->get_references(self :: REFERENCE_PREVIOUS);
    }

    /**
     *
     * @param $previous_references multitype:HistoryReference
     */
    public function set_previous_references($previous_references)
    {
        $this->set_references($previous_references, self :: REFERENCE_PREVIOUS);
    }

    /**
     *
     * @param $single integer
     * @return boolean
     */
    public function has_previous_references($single = false)
    {
        return $this->has_references(self :: REFERENCE_PREVIOUS, $single);
    }

    /**
     *
     * @param $previous_reference HistoryReference
     */
    public function add_previous_reference(HistoryReference $previous_reference)
    {
        $this->add_reference($previous_reference, self :: REFERENCE_PREVIOUS);
    }

    /**
     *
     * @return multitype:HistoryReference
     */
    public function get_next_references()
    {
        return $this->get_references(self :: REFERENCE_NEXT);
    }

    /**
     *
     * @param $next_references multitype:HistoryReference
     */
    public function set_next_references($next_references)
    {
        $this->set_references($next_references, self :: REFERENCE_NEXT);
    }

    /**
     *
     * @param $single boolean
     * @return boolean
     */
    public function has_next_references($single = false)
    {
        return $this->has_references(self :: REFERENCE_NEXT, $single);
    }

    /**
     *
     * @param $next_reference HistoryReference
     */
    public function add_next_reference(HistoryReference $next_reference)
    {
        $this->add_reference($next_reference, self :: REFERENCE_NEXT);
    }

    /**
     *
     * @param $type integer
     * @return multitype:HistoryReference
     */
    public function get_references($type)
    {
        return $this->references[$type];
    }

    /**
     *
     * @param $references multitype:HistoryReference
     * @param $type integer
     */
    public function set_references($references, $type)
    {
        $this->references[$type] = $references;
    }

    /**
     *
     * @param $type integer
     * @param $single boolean
     * @return boolean
     */
    public function has_references($type, $single = false)
    {
        return $single ? (count($this->references[$type]) == 1) : (count($this->references[$type]) > 0);
    }

    /**
     *
     * @param $reference HistoryReference
     * @param $type integer
     */
    public function add_reference(HistoryReference $reference, $type)
    {
        $this->references[$type][] = $reference;
    }
}
