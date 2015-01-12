<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use application\discovery\data_source\bamaflex\HistoryReference;
use application\discovery\module\faculty\DataManager;

class Faculty extends \application\discovery\module\faculty\Faculty
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SOURCE = 'source';
    const REFERENCE_PREVIOUS = 1;
    const REFERENCE_NEXT = 2;

    /**
     *
     * @var multitype:string
     */
    private $deans;

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
     * @param $source int
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     *
     * @param $module_instance Instance
     * @param $recursive boolean
     * @return multitype:string:Faculty
     */
    public function get_previous($module_instance, $recursive = true)
    {
        $faculties = array();
        if ($this->has_previous_references())
        {
            foreach ($this->get_previous_references() as $previous_reference)
            {
                $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters();
                $parameters->set_faculty_id($previous_reference->get_id());
                $parameters->set_source($previous_reference->get_source());
                
                $faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($parameters);
                if ($faculty instanceof Faculty)
                {
                    $faculties[$faculty->get_year()][] = $faculty;
                }
                
                if ($faculty->has_next_references(true) && $this->has_previous_references(true) && $recursive)
                {
                    $faculties = array_merge_recursive($faculties, $faculty->get_previous($module_instance));
                }
            }
        }
        return $faculties;
    }

    /**
     *
     * @param $module_instance Instance
     * @param $recursive boolean
     * @return multitype:string:Faculty
     */
    public function get_next($module_instance, $recursive = true)
    {
        $faculties = array();
        if ($this->has_next_references())
        {
            foreach ($this->get_next_references() as $next_reference)
            {
                $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters();
                $parameters->set_faculty_id($next_reference->get_id());
                $parameters->set_source($next_reference->get_source());
                
                $faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($parameters);
                if ($faculty instanceof Faculty)
                {
                    $faculties[$faculty->get_year()][] = $faculty;
                }
                
                if ($faculty->has_previous_references(true) && $this->has_next_references(true) && $recursive)
                {
                    $faculties = array_merge_recursive($faculties, $faculty->get_next($module_instance));
                }
            }
        }
        return $faculties;
    }

    /**
     *
     * @param $module_instance Instance
     * @return multitype:string:Faculty
     */
    public function get_all($module_instance)
    {
        $faculties = $this->get_next($module_instance);
        $faculties[$this->get_year()][] = $this;
        $faculties = array_merge_recursive($faculties, $this->get_previous($module_instance));
        
        ksort($faculties);
        
        return $faculties;
    }

    /**
     *
     * @return multitype:string
     */
    public function get_deans()
    {
        return $this->deans;
    }

    /**
     *
     * @param $deans multitype:string
     */
    public function set_deans($deans)
    {
        $this->deans = $deans;
    }

    /**
     *
     * @return boolean
     */
    public function has_deans()
    {
        return count($this->deans) > 0;
    }

    /**
     *
     * @param $dean string
     */
    public function add_dean($dean)
    {
        $this->deans[] = $dean;
    }

    /**
     *
     * @return string
     */
    public function get_deans_string()
    {
        $deans = array();
        foreach ($this->get_deans() as $dean)
        {
            $deans[] = $dean->get_person();
        }
        return implode(', ', $deans);
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

    /**
     *
     * @param $extended_property_names multitype:string
     * @return $extended_property_names multitype:string
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        
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
