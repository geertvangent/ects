<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Ehb\Application\Discovery\DiscoveryItem;
use Ehb\Application\Discovery\Module\Course\DataManager;
use Chamilo\Libraries\Utilities\StringUtilities;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.module.courses.discovery
 * 
 * @author Hans De Bisschop
 */
class Course extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Course properties
     */
    const PROPERTY_PARENT_ID = 'parent_id';
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
    const PROPERTY_SCORE_CALCULATION = 'score_calculation';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_PREVIOUS_PARENT_ID = 'previous_parent_id';
    const PROPERTY_NEXT_ID = 'next_id';
    const PROPERTY_APPROVED = 'approved';
    const PROPERTY_EXCHANGE = 'exchange';
    const TIMEFRAME_ACADEMIC_YEAR = '1';
    const TIMEFRAME_FIRST_TERM = '2';
    const TIMEFRAME_SECOND_TERM = '3';
    const TIMEFRAME_BOTH_TERMS = '4';
    const TIMEFRAME_UNKNOWN = '5';
    const PROGRAMME_TYPE_SIMPLE = 1;
    const PROGRAMME_TYPE_COMPLEX = 2;
    const PROGRAMME_TYPE_PART = 4;
    const RESULT_SCALE_NUMBER = 1;
    const RESULT_SCALE_PASS_FAIL = 2;
    const RESULT_SCALE_ADVICE = 3;
    const SCORE_CALCULATION_NUMBER = 1;
    const SCORE_CALCULATION_DIFFERENCE = 7;

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

    private $children;

    private $materials_by_type;

    private $teachers_by_type;

    private $costs_by_type;

    private $activities_by_type;

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PARENT_ID;
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
        $extended_property_names[] = self :: PROPERTY_SCORE_CALCULATION;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_ID;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_PARENT_ID;
        $extended_property_names[] = self :: PROPERTY_NEXT_ID;
        $extended_property_names[] = self :: PROPERTY_APPROVED;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    public function get_parent_id()
    {
        return $this->get_default_property(self :: PROPERTY_PARENT_ID);
    }

    public function set_parent_id($parent_id)
    {
        $this->set_default_property(self :: PROPERTY_PARENT_ID, $parent_id);
    }

    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
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

    public function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    public function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    public function get_training()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING);
    }

    public function set_training($training)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING, $training);
    }

    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    public function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    public function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    public function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    public function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    public function get_programme_type()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_TYPE);
    }

    public function set_programme_type($programme_type)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_TYPE, $programme_type);
    }

    /**
     *
     * @return string
     */
    public function get_programme_type_string()
    {
        return self :: programme_type_string($this->get_programme_type());
    }

    /**
     *
     * @return string
     */
    public static function programme_type_string($programme_type)
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

    public function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    public function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    public function get_timeframe_visual_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_VISUAL_ID);
    }

    public function set_timeframe_visual_id($timeframe_visual_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_VISUAL_ID, $timeframe_visual_id);
    }

    /**
     *
     * @return string
     */
    public function get_timeframe()
    {
        return self :: timeframe($this->get_timeframe_visual_id());
    }

    /**
     *
     * @return string
     */
    public static function timeframe($timeframe_id)
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

    public function get_timeframe_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_ID);
    }

    public function set_timeframe_id($timeframe_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_ID, $timeframe_id);
    }

    public function get_result_scale_id()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT_SCALE_ID);
    }

    public function set_result_scale_id($result_scale_id)
    {
        $this->set_default_property(self :: PROPERTY_RESULT_SCALE_ID, $result_scale_id);
    }

    public function get_previous_id()
    {
        return $this->get_default_property(self :: PROPERTY_PREVIOUS_ID);
    }

    public function set_previous_id($previous_id)
    {
        $this->set_default_property(self :: PROPERTY_PREVIOUS_ID, $previous_id);
    }

    public function get_previous_parent_id()
    {
        return $this->get_default_property(self :: PROPERTY_PREVIOUS_PARENT_ID);
    }

    public function set_previous_parent_id($previous_parent_id)
    {
        $this->set_default_property(self :: PROPERTY_PREVIOUS_PARENT_ID, $previous_parent_id);
    }

    public function get_next_id()
    {
        return $this->get_default_property(self :: PROPERTY_NEXT_ID);
    }

    public function set_next_id($next_id)
    {
        $this->set_default_property(self :: PROPERTY_NEXT_ID, $next_id);
    }

    public function get_approved()
    {
        return $this->get_default_property(self :: PROPERTY_APPROVED);
    }

    public function set_approved($approved)
    {
        $this->set_default_property(self :: PROPERTY_APPROVED, $approved);
    }

    public function get_exchange()
    {
        return $this->get_default_property(self :: PROPERTY_EXCHANGE);
    }

    public function set_exchange($exchange)
    {
        $this->set_default_property(self :: PROPERTY_APPROVED, $exchange);
    }

    public function get_previous($module_instance, $recursive = true)
    {
        $courses = array();
        $course = $this;
        if ($this->get_previous_id())
        {
            do
            {
                $parameters = new Parameters($course->get_previous_id(), $course->get_source());
                
                $course = DataManager :: get_instance($module_instance)->retrieve_course($parameters);
                $courses[] = $course;
            }
            while ($course instanceof Course && $course->get_previous_id() && $recursive);
        }
        return $courses;
    }

    public function get_next($module_instance, $recursive = true)
    {
        $courses = array();
        $course = $this;
        if ($this->get_next_id())
        {
            do
            {
                $parameters = new Parameters($course->get_next_id(), $course->get_source());
                
                $course = DataManager :: get_instance($module_instance)->retrieve_course($parameters);
                $courses[] = $course;
            }
            while ($course instanceof Course && $course->get_next_id() && $recursive);
        }
        return $courses;
    }

    public function get_all($module_instance)
    {
        $courses = $this->get_next($module_instance);
        array_unshift($courses, $this);
        
        foreach ($this->get_previous($module_instance) as $course)
        {
            array_unshift($courses, $course);
        }
        return $courses;
    }

    /**
     *
     * @return string
     */
    public function get_result_scale_string()
    {
        return self :: result_scale_string($this->get_result_scale_id());
    }

    /**
     *
     * @return string
     */
    public static function result_scale_string($result_scale_id)
    {
        switch ($result_scale_id)
        {
            case self :: RESULT_SCALE_ADVICE :
                return 'ResultScaleAdvice';
                break;
            case self :: RESULT_SCALE_PASS_FAIL :
                return 'ResultScalePassFail';
                break;
            case self :: RESULT_SCALE_NUMBER :
                return 'ResultScaleNumber';
                break;
        }
    }

    public function get_deliberation()
    {
        return $this->get_default_property(self :: PROPERTY_DELIBERATION);
    }

    public function set_deliberation($deliberation)
    {
        $this->set_default_property(self :: PROPERTY_DELIBERATION, $deliberation);
    }

    public function get_level()
    {
        return $this->get_default_property(self :: PROPERTY_LEVEL);
    }

    public function set_level($level)
    {
        $this->set_default_property(self :: PROPERTY_LEVEL, $level);
    }

    public function get_kind()
    {
        return $this->get_default_property(self :: PROPERTY_KIND);
    }

    public function set_kind($kind)
    {
        $this->set_default_property(self :: PROPERTY_KIND, $kind);
    }

    public function get_goals()
    {
        return $this->get_default_property(self :: PROPERTY_GOALS);
    }

    public function set_goals($goals)
    {
        $this->set_default_property(self :: PROPERTY_GOALS, $goals);
    }

    public function get_contents()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENTS);
    }

    public function set_contents($contents)
    {
        $this->set_default_property(self :: PROPERTY_CONTENTS, $contents);
    }

    public function has_content($include_children = true)
    {
        if ($include_children && $this->get_programme_type() == self :: PROGRAMME_TYPE_COMPLEX)
        {
            foreach ($this->get_children() as $child)
            {
                if ($child->has_content())
                {
                    return true;
                }
            }
        }
        
        if (! StringUtilities :: is_null_or_empty($this->get_goals(), true))
        {
            return true;
        }
        
        if (! StringUtilities :: is_null_or_empty($this->get_contents(), true))
        {
            return true;
        }
        
        if (! StringUtilities :: is_null_or_empty($this->get_coaching(), true))
        {
            return true;
        }
        
        if (! StringUtilities :: is_null_or_empty($this->get_succession(), true))
        {
            return true;
        }
        
        return false;
    }

    public function get_coaching()
    {
        return $this->get_default_property(self :: PROPERTY_COACHING);
    }

    public function set_coaching($coaching)
    {
        $this->set_default_property(self :: PROPERTY_COACHING, $coaching);
    }

    public function get_succession()
    {
        return $this->get_default_property(self :: PROPERTY_SUCCESSION);
    }

    public function set_succession($succession)
    {
        $this->set_default_property(self :: PROPERTY_SUCCESSION, $succession);
    }

    public function get_jury()
    {
        return $this->get_default_property(self :: PROPERTY_JURY);
    }

    public function set_jury($jury)
    {
        $this->set_default_property(self :: PROPERTY_JURY, $jury);
    }

    public function get_repleacable()
    {
        return $this->get_default_property(self :: PROPERTY_REPLEACABLE);
    }

    public function set_repleacable($repleacable)
    {
        $this->set_default_property(self :: PROPERTY_REPLEACABLE, $repleacable);
    }

    public function training_unit()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_UNIT);
    }

    public function set_training_unit($training_unit)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_UNIT, $training_unit);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    /**
     *
     * @return the $second_chance
     */
    public function get_second_chance()
    {
        return $this->second_chance;
    }

    /**
     *
     * @param field_type $second_chance
     */
    public function set_second_chance($second_chance)
    {
        $this->second_chance = $second_chance;
    }

    /**
     *
     * @return the $following_impossible
     */
    public function get_following_impossible()
    {
        return $this->following_impossible;
    }

    /**
     *
     * @param field_type $following_impossible
     */
    public function set_following_impossible($following_impossible)
    {
        $this->following_impossible = $following_impossible;
    }

    /**
     *
     * @return the $costs
     */
    public function get_costs()
    {
        return $this->costs;
    }

    /**
     *
     * @param field_type $costs
     */
    public function set_costs($costs)
    {
        $this->costs = $costs;
    }

    public function get_costs_by_type($type = null)
    {
        if (is_null($type))
        {
            return $this->get_costs();
        }
        
        if (! isset($this->costs_by_type[$type]))
        {
            $this->costs_by_type[Cost :: TYPE_MATERIAL] = false;
            $this->costs_by_type[Cost :: TYPE_ADDITIONAL] = false;
            
            foreach ($this->get_costs() as $cost)
            {
                $this->costs_by_type[$cost->get_type()] = $cost;
            }
        }
        return $this->costs_by_type[$type];
    }

    /**
     *
     * @return the $evaluations
     */
    public function get_evaluations()
    {
        return $this->evaluations;
    }

    /**
     *
     * @param field_type $evaluations
     */
    public function set_evaluations($evaluations)
    {
        $this->evaluations = $evaluations;
    }

    public function has_evaluations($include_children = true)
    {
        if ($include_children && $this->get_programme_type() == self :: PROGRAMME_TYPE_COMPLEX)
        {
            foreach ($this->get_children() as $child)
            {
                if ($child->has_evaluations())
                {
                    return true;
                }
            }
        }
        
        if (count($this->get_evaluations()) > 0)
        {
            return true;
        }
        
        return false;
    }

    /**
     *
     * @return the $activities
     */
    public function get_activities()
    {
        return $this->activities;
    }

    /**
     *
     * @param field_type $activities
     */
    public function set_activities($activities)
    {
        $this->activities = $activities;
    }

    public function has_activities($include_children = true)
    {
        if ($include_children && $this->get_programme_type() == self :: PROGRAMME_TYPE_COMPLEX)
        {
            foreach ($this->get_children() as $child)
            {
                if ($child->has_activities())
                {
                    return true;
                }
            }
        }
        
        if (count($this->get_activities()) > 0)
        {
            return true;
        }
        
        return false;
    }

    public function get_activities_by_type($type = null)
    {
        if (is_null($type))
        {
            return $this->get_activities();
        }
        
        if (! isset($this->activities_by_type[$type]))
        {
            $this->activities_by_type[ActivityDescription :: CLASS_NAME] = array();
            $this->activities_by_type[ActivityStructured :: CLASS_NAME] = array();
            $this->activities_by_type[ActivityTotal :: CLASS_NAME] = false;
            
            foreach ($this->get_activities() as $activity)
            {
                if ($activity instanceof ActivityTotal)
                {
                    $this->activities_by_type[$activity :: CLASS_NAME] = $activity;
                }
                else
                {
                    $this->activities_by_type[$activity :: CLASS_NAME][] = $activity;
                }
            }
        }
        return $this->activities_by_type[$type];
    }

    /**
     *
     * @return the $materials
     */
    public function get_materials()
    {
        return $this->materials;
    }

    /**
     *
     * @param field_type $materials
     */
    public function set_materials($materials)
    {
        $this->materials = $materials;
    }

    public function get_materials_by_type($type = null)
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

    public function has_materials($type, $include_children = true)
    {
        if ($include_children && $this->get_programme_type() == self :: PROGRAMME_TYPE_COMPLEX)
        {
            foreach ($this->get_children() as $child)
            {
                if ($child->has_materials($type))
                {
                    return true;
                }
            }
        }
        
        if (count($this->get_materials_by_type($type)) > 0)
        {
            return true;
        }
        
        return false;
    }

    /**
     *
     * @return the $competences
     */
    public function get_competences()
    {
        return $this->competences;
    }

    /**
     *
     * @param field_type $competences
     */
    public function set_competences($competences)
    {
        $this->competences = $competences;
    }

    public function get_competences_by_type($type = null)
    {
        if (is_null($type))
        {
            return $this->get_competences();
        }
        
        if (! isset($this->competences_by_type[$type]))
        {
            $this->competences_by_type[Competence :: TYPE_BEGIN] = array();
            $this->competences_by_type[Competence :: TYPE_END] = array();
            
            foreach ($this->get_competences() as $competence)
            {
                $this->competences_by_type[$competence->get_type()][] = $competence;
            }
        }
        return $this->competences_by_type[$type];
    }

    public function has_competences($type, $include_children = true)
    {
        if ($include_children && $this->get_programme_type() == self :: PROGRAMME_TYPE_COMPLEX)
        {
            foreach ($this->get_children() as $child)
            {
                if ($child->has_competences($type))
                {
                    return true;
                }
            }
        }
        
        if (count($this->get_competences_by_type($type)) > 0)
        {
            return true;
        }
        
        return false;
    }

    /**
     *
     * @return the $languages
     */
    public function get_languages()
    {
        return $this->languages;
    }

    public function get_languages_string()
    {
        return implode(', ', $this->get_languages());
    }

    /**
     *
     * @param field_type $languages
     */
    public function set_languages($languages)
    {
        $this->languages = $languages;
    }

    public function has_languages()
    {
        return count($this->get_languages()) > 0;
    }

    /**
     *
     * @return the $timeframe_parts
     */
    public function get_timeframe_parts()
    {
        return $this->timeframe_parts;
    }

    public function get_timeframe_parts_string()
    {
        return implode(', ', $this->get_timeframe_parts());
    }

    /**
     *
     * @param field_type $timeframe_parts
     */
    public function set_timeframe_parts($timeframe_parts)
    {
        $this->timeframe_parts = $timeframe_parts;
    }

    /**
     *
     * @return the $teachers
     */
    public function get_teachers()
    {
        return $this->teachers;
    }

    public function get_teachers_by_type($type)
    {
        if (is_null($type))
        {
            return $this->get_teachers();
        }
        
        if (! isset($this->teachers_by_type[$type]))
        {
            $this->teachers_by_type[Teacher :: TYPE_TEACHER] = array();
            $this->teachers_by_type[Teacher :: TYPE_COORDINATOR] = array();
            
            foreach ($this->get_teachers() as $teacher)
            {
                $this->teachers_by_type[$teacher->is_coordinator()][] = $teacher;
            }
        }
        return $this->teachers_by_type[$type];
    }

    public function has_coordinators()
    {
        return count($this->get_teachers_by_type(Teacher :: TYPE_COORDINATOR)) > 0;
    }

    public function has_teachers()
    {
        return count($this->get_teachers_by_type(Teacher :: TYPE_TEACHER)) > 0;
    }

    public function get_coordinators_string()
    {
        $coordinators = array();
        foreach ($this->get_teachers() as $coordinator)
        {
            if ($coordinator->is_coordinator())
            {
                $coordinators[] = $coordinator;
            }
        }
        return implode(', ', $coordinators);
    }

    public function get_teachers_string()
    {
        $teachers = array();
        foreach ($this->get_teachers() as $teacher)
        {
            if (! $teacher->is_coordinator())
            {
                $teachers[] = $teacher;
            }
        }
        return implode(', ', $teachers);
    }

    public function get_score_calculation()
    {
        return $this->get_default_property(self :: PROPERTY_SCORE_CALCULATION);
    }

    public function set_score_calculation($score_calculation)
    {
        $this->set_default_property(self :: PROPERTY_SCORE_CALCULATION, $score_calculation);
    }

    public function get_score_calculation_string()
    {
        return self :: score_calculation_string($this->get_score_calculation());
    }

    /**
     *
     * @return string
     */
    public static function score_calculation_string($score_calculation)
    {
        switch ($score_calculation)
        {
            case self :: SCORE_CALCULATION_NUMBER :
                return 'ScoreCalculationNumber';
                break;
            case self :: SCORE_CALCULATION_DIFFERENCE :
                
                return 'ScoreCalculationDifference';
                break;
        }
    }

    /**
     *
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

    public function set_children($children)
    {
        $this->children = $children;
    }

    public function add_child($child)
    {
        $this->children[] = $child;
    }

    public function get_children()
    {
        // if ($this->get_programme_type() == self :: PROGRAMME_TYPE_COMPLEX)
        // {
        // if (! isset($this->children))
        // {
        //
        // }
        // }
        // else
        // {
        // $this->children = array();
        // }
        return $this->children;
    }

    public function has_children()
    {
        return count($this->get_children()) > 0;
    }
}
