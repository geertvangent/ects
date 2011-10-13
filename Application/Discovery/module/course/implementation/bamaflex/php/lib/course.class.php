<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryItem;
use application\discovery\DiscoveryDataManager;

/**
 * application.discovery.module.courses.discovery
 * @author Hans De Bisschop
 */
class Course extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Course properties
     */
    const PROPERTY_YEAR = 'year';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_NAME = 'name';
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_PROGRAMME_TYPE = 'programme_type';
    const PROPERTY_WEIGHT = 'weight';
    const PROPERTY_TIMEFRAME_VISUAL_ID = 'timeframe_visual_id';
    const PROPERTY_TIMEFRAME_ID = 'timeframe_id';
    const PROPERTY_RESULT_SCALE_ID = 'result_scale_id';
    const PROPERTY_DELIBERATION = 'deliberation';
    const PROPERTY_LEVEL = 'level';
    const PROPERTY_KIND = 'kind';
    const PROPERTY_GOALS = 'goals';
    const PROPERTY_CONTENTS = 'contents';
    const PROPERTY_COACHING = 'coaching';
    const PROPERTY_SUCCESSION = 'succession';
    const PROPERTY_JURY = 'jury';
    const PROPERTY_REPLEACABLE = 'repleacable';
    const PROPERTY_TRAINING_UNIT = 'training_unit';
    
    const TIMEFRAME_ACADEMIC_YEAR = '1';
    const TIMEFRAME_FIRST_TERM = '2';
    const TIMEFRAME_SECOND_TERM = '3';
    const TIMEFRAME_BOTH_TERMS = '4';
    const TIMEFRAME_UNKNOWN = '5';
    
    const PROGRAMME_TYPE_SIMPLE = 1;
    const PROGRAMME_TYPE_COMPLEX = 2;
    const PROGRAMME_TYPE_PART = 4;
    
    private $second_chance;
    private $following_impossible;
    private $costs;
    private $evaluations;
    private $activities;
    private $materials;
    private $competences;
    private $languages;
    private $timeframe_parts;
    private $teachers;
    
    private $materials_by_type;

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_TRAINING;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_TYPE;
        $extended_property_names[] = self :: PROPERTY_WEIGHT;
        $extended_property_names[] = self :: PROPERTY_TIMEFRAME_VISUAL_ID;
        $extended_property_names[] = self :: PROPERTY_TIMEFRAME_ID;
        $extended_property_names[] = self :: PROPERTY_RESULT_SCALE_ID;
        $extended_property_names[] = self :: PROPERTY_DELIBERATION;
        $extended_property_names[] = self :: PROPERTY_LEVEL;
        $extended_property_names[] = self :: PROPERTY_KIND;
        $extended_property_names[] = self :: PROPERTY_GOALS;
        $extended_property_names[] = self :: PROPERTY_CONTENTS;
        $extended_property_names[] = self :: PROPERTY_COACHING;
        $extended_property_names[] = self :: PROPERTY_SUCCESSION;
        $extended_property_names[] = self :: PROPERTY_JURY;
        $extended_property_names[] = self :: PROPERTY_REPLEACABLE;
        $extended_property_names[] = self :: PROPERTY_TRAINING_UNIT;
        
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

    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    function get_training()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING);
    }

    function set_training($training)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING, $training);
    }

    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    function get_programme_type()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_TYPE);
    }

    function set_programme_type($programme_type)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_TYPE, $programme_type);
    }

    /**
     * @return string
     */
    function get_programme_type_string()
    {
        return self :: programme_type_string($this->get_programme_type());
    }

    /**
     * @return string
     */
    static function programme_type_string($programme_type)
    {
        switch ($programme_type)
        {
            case self :: PROGRAMME_TYPE_SIMPLE :
                return 'SimpleCourse';
                break;
            case self :: PROGRAMME_TYPE_COMPLEX :
                return 'ComplexCourse';
                break;
            case self :: PROGRAMME_TYPE_PART :
                return 'ComplexCoursePart';
                break;
        }
    }

    function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    function get_timeframe_visual_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_VISUAL_ID);
    }

    function set_timeframe_visual_id($timeframe_visual_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_VISUAL_ID, $timeframe_visual_id);
    }

    /**
     * @return string
     */
    function get_timeframe()
    {
        return self :: timeframe($this->get_timeframe_visual_id());
    }

    /**
     * @return string
     */
    static function timeframe($timeframe_id)
    {
        switch ($timeframe_id)
        {
            case self :: TIMEFRAME_ACADEMIC_YEAR :
                return 'AcademicYear';
                break;
            case self :: TIMEFRAME_FIRST_TERM :
                return 'FirstTerm';
                break;
            case self :: TIMEFRAME_SECOND_TERM :
                return 'SecondTerm';
                break;
            case self :: TIMEFRAME_BOTH_TERMS :
                return 'BothTerms';
                break;
            case self :: TIMEFRAME_UNKNOWN :
                return 'Unknown';
                break;
        }
    }

    function get_timeframe_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_ID);
    }

    function set_timeframe_id($timeframe_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_ID, $timeframe_id);
    }

    function get_result_scale_id()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT_SCALE_ID);
    }

    function set_result_scale_id($result_scale_id)
    {
        $this->set_default_property(self :: PROPERTY_RESULT_SCALE_ID, $result_scale_id);
    }

    function get_deliberation()
    {
        return $this->get_default_property(self :: PROPERTY_DELIBERATION);
    }

    function set_deliberation($deliberation)
    {
        $this->set_default_property(self :: PROPERTY_DELIBERATION, $deliberation);
    }

    function get_level()
    {
        return $this->get_default_property(self :: PROPERTY_LEVEL);
    }

    function set_level($level)
    {
        $this->set_default_property(self :: PROPERTY_LEVEL, $level);
    }

    function get_kind()
    {
        return $this->get_default_property(self :: PROPERTY_KIND);
    }

    function set_kind($kind)
    {
        $this->set_default_property(self :: PROPERTY_KIND, $kind);
    }

    function get_goals()
    {
        return $this->get_default_property(self :: PROPERTY_GOALS);
    }

    function set_goals($goals)
    {
        $this->set_default_property(self :: PROPERTY_GOALS, $goals);
    }

    function get_contents()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENTS);
    }

    function set_contents($contents)
    {
        $this->set_default_property(self :: PROPERTY_CONTENTS, $contents);
    }

    function get_coaching()
    {
        return $this->get_default_property(self :: PROPERTY_COACHING);
    }

    function set_coaching($coaching)
    {
        $this->set_default_property(self :: PROPERTY_COACHING, $coaching);
    }

    function get_succession()
    {
        return $this->get_default_property(self :: PROPERTY_SUCCESSION);
    }

    function set_succession($succession)
    {
        $this->set_default_property(self :: PROPERTY_SUCCESSION, $succession);
    }

    function get_jury()
    {
        return $this->get_default_property(self :: PROPERTY_JURY);
    }

    function set_jury($jury)
    {
        $this->set_default_property(self :: PROPERTY_JURY, $jury);
    }

    function get_repleacable()
    {
        return $this->get_default_property(self :: PROPERTY_REPLEACABLE);
    }

    function set_repleacable($repleacable)
    {
        $this->set_default_property(self :: PROPERTY_REPLEACABLE, $repleacable);
    }

    function training_unit()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_UNIT);
    }

    function set_training_unit($training_unit)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_UNIT, $training_unit);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    /**
     * @return the $second_chance
     */
    public function get_second_chance()
    {
        return $this->second_chance;
    }

    /**
     * @param field_type $second_chance
     */
    public function set_second_chance($second_chance)
    {
        $this->second_chance = $second_chance;
    }

    /**
     * @return the $following_impossible
     */
    public function get_following_impossible()
    {
        return $this->following_impossible;
    }

    /**
     * @param field_type $following_impossible
     */
    public function set_following_impossible($following_impossible)
    {
        $this->following_impossible = $following_impossible;
    }

    /**
     * @return the $costs
     */
    public function get_costs()
    {
        return $this->costs;
    }

    /**
     * @param field_type $costs
     */
    public function set_costs($costs)
    {
        $this->costs = $costs;
    }

    /**
     * @return the $evaluations
     */
    public function get_evaluations()
    {
        return $this->evaluations;
    }

    /**
     * @param field_type $evaluations
     */
    public function set_evaluations($evaluations)
    {
        $this->evaluations = $evaluations;
    }

    /**
     * @return the $activities
     */
    public function get_activities()
    {
        return $this->activities;
    }

    /**
     * @param field_type $activities
     */
    public function set_activities($activities)
    {
        $this->activities = $activities;
    }

    /**
     * @return the $materials
     */
    public function get_materials()
    {
        return $this->materials;
    }

    /**
     * @param field_type $materials
     */
    public function set_materials($materials)
    {
        $this->materials = $materials;
    }

    function get_materials_by_type($type = null)
    {
        if (is_null($type))
        {
            return $this->get_materials();
        }
        
        if (! isset($this->materials_by_type[$type]))
        {
            $this->materials_by_type[Material :: TYPE_OPTIONAL] = array();
            $this->materials_by_type[Material :: TYPE_REQUIRED] = array();
            
            foreach ($this->get_materials() as $material)
            {
                $this->materials_by_type[$material->get_type()][] = $material;
            }
        }
        return $this->materials_by_type[$type];
    }

    /**
     * @return the $competences
     */
    public function get_competences()
    {
        return $this->competences;
    }

    /**
     * @param field_type $competences
     */
    public function set_competences($competences)
    {
        $this->competences = $competences;
    }

    /**
     * @return the $languages
     */
    public function get_languages()
    {
        return $this->languages;
    }

    function get_languages_string()
    {
        return implode(', ', $this->get_languages());
    }

    /**
     * @param field_type $languages
     */
    public function set_languages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return the $timeframe_parts
     */
    public function get_timeframe_parts()
    {
        return $this->timeframe_parts;
    }

    function get_timeframe_parts_string()
    {
        return implode(', ', $this->get_timeframe_parts());
    }

    /**
     * @param field_type $timeframe_parts
     */
    public function set_timeframe_parts($timeframe_parts)
    {
        $this->timeframe_parts = $timeframe_parts;
    }

    /**
     * @return the $teachers
     */
    public function get_teachers()
    {
        return $this->teachers;
    }

    function get_teachers_string()
    {
        $teachers = array();
        foreach ($this->get_teachers() as $teacher)
        {
            if ($teacher->is_coordinator())
            {
                $teachers[] = $teacher;
            }
        }
        return implode(', ', $teachers);
    }

    /**
     * @param field_type $teachers
     */
    public function set_teachers($teachers)
    {
        $this->teachers = $teachers;
    }

    public function add_teacher($teacher)
    {
        $this->teachers[] = $teacher;
    }

    public function add_timeframe_part($timeframe_part)
    {
        $this->timeframe_parts[] = $timeframe_part;
    }

    public function add_language($language)
    {
        $this->languages[] = $language;
    }

    public function add_competence($competence)
    {
        $this->competences[] = $competence;
    }

    public function add_material($material)
    {
        $this->materials[] = $material;
    }

    public function add_activity($activity)
    {
        $this->activities[] = $activity;
    }

    public function add_evaluation($evaluation)
    {
        $this->evaluations[] = $evaluation;
    }

    public function add_cost($cost)
    {
        $this->costs[] = $cost;
    }

}
