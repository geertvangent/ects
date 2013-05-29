<?php
namespace application\discovery\module\course\implementation\bamaflex;

use common\libraries\DoctrineConditionTranslator;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\StringUtilities;
use application\discovery\module\course\DataManagerInterface;
use stdClass;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $course = array();

    private $evaluations = array();

    private $activities = array();

    private $materials = array();

    private $competences = array();

    private $languages = array();

    private $timeframe_parts = array();

    private $teachers = array();

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    public function retrieve_course($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->course[$programme_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                $result = $statement->fetch(\PDO :: FETCH_OBJ);
                
                if ($result instanceof stdClass)
                {
                    $this->course[$programme_id][$source] = $this->result_to_course($course_parameters, $result);
                }
            }
        }
        return $this->course[$programme_id][$source];
    }

    public function result_to_course($course_parameters, $object)
    {
        $course = new Course();
        $course->set_id($object->id);
        $course->set_year($object->year);
        $course->set_faculty_id($object->faculty_id);
        $course->set_faculty($this->convert_to_utf8($object->faculty));
        $course->set_training_id($object->training_id);
        $course->set_training($this->convert_to_utf8($object->training));
        $course->set_name($this->convert_to_utf8($object->name));
        $course->set_source($object->source);
        $course->set_trajectory_part($object->trajectory_part);
        $course->set_credits($object->credits);
        $course->set_programme_type($object->programme_type);
        $course->set_weight($object->weight);
        $course->set_timeframe_visual_id($object->timeframe_visual_id);
        $course->set_timeframe_id($object->timeframe_id);
        $course->set_result_scale_id($object->result_scale_id);
        $course->set_deliberation($object->deliberation);
        $course->set_score_calculation($object->score_calculation);
        $course->set_level($this->convert_to_utf8($object->level));
        $course->set_kind($this->convert_to_utf8($object->kind));
        $course->set_goals($this->convert_to_utf8($object->goals));
        $course->set_contents($this->convert_to_utf8($object->contents));
        $course->set_coaching($this->convert_to_utf8($object->coaching));
        $course->set_succession($this->convert_to_utf8($object->succession));
        $course->set_jury($object->jury);
        $course->set_repleacable($object->repleacable);
        $course->set_training_unit($this->convert_to_utf8($object->training_unit));
        $course->set_approved($object->approved);
        $course->set_previous_id($object->previous_id);
        $course->set_previous_parent_id($object->previous_parent_id);
        $course->set_next_id($this->retrieve_course_next_id($course));
        
        $second_chance = new SecondChance();
        $second_chance->set_exam($object->second_exam_chance);
        $second_chance->set_enrollment($object->second_enrollment);
        $second_chance->set_exam_parts($object->second_exam_parts);
        $course->set_second_chance($second_chance);
        
        $following_impossible = new FollowingImpossible();
        $following_impossible->set_credit($object->impossible_credit);
        $following_impossible->set_exam_credit($object->impossible_exam_credit);
        $following_impossible->set_exam_degree($object->impossible_exam_degree);
        $course->set_following_impossible($following_impossible);
        
        if (! StringUtilities :: is_null_or_empty($object->total_material_price, true))
        {
            $cost = new Cost();
            $cost->set_type(Cost :: TYPE_MATERIAL);
            $cost->set_price($object->total_material_price);
            $course->add_cost($cost);
        }
        
        if (! StringUtilities :: is_null_or_empty($object->additional_costs, true))
        {
            $cost = new Cost();
            $cost->set_type(Cost :: TYPE_ADDITIONAL);
            $cost->set_price($this->convert_to_utf8($object->additional_costs));
            $course->add_cost($cost);
        }
        
        if (! StringUtilities :: is_null_or_empty($object->evaluation, true))
        {
            $evaluation_description = new EvaluationDescription();
            $evaluation_description->set_description($this->convert_to_utf8($object->evaluation));
            $course->add_evaluation($evaluation_description);
        }
        
        foreach ($this->retrieve_evaluations($course_parameters) as $evaluation)
        {
            $course->add_evaluation($evaluation);
        }
        
        if (! StringUtilities :: is_null_or_empty($object->activities, true))
        {
            $activity_description = new ActivityDescription();
            $activity_description->set_description($this->convert_to_utf8($object->activities));
            $course->add_activity($activity_description);
        }
        
        foreach ($this->retrieve_activities($course_parameters) as $activity)
        {
            $course->add_activity($activity);
        }
        
        $activity_total = new ActivityTotal();
        $activity_total->set_time($object->total_study_time);
        $course->add_activity($activity_total);
        
        if (! StringUtilities :: is_null_or_empty($object->material_required, true))
        {
            $material_description = new MaterialDescription();
            $material_description->set_type(Material :: TYPE_REQUIRED);
            $material_description->set_description($this->convert_to_utf8($object->material_required));
            $course->add_material($material_description);
        }
        
        if (! StringUtilities :: is_null_or_empty($object->material_optional, true))
        {
            $material_description = new MaterialDescription();
            $material_description->set_type(Material :: TYPE_OPTIONAL);
            $material_description->set_description(strip_tags($this->convert_to_utf8($object->material_optional)));
            $course->add_material($material_description);
        }
        
        foreach ($this->retrieve_materials($course_parameters) as $material)
        {
            $course->add_material($material);
        }
        
        if (! StringUtilities :: is_null_or_empty($object->competences_start, true))
        {
            $competence_description = new CompetenceDescription();
            $competence_description->set_type(Competence :: TYPE_BEGIN);
            $competence_description->set_description($this->convert_to_utf8($object->competences_start));
            $course->add_competence($competence_description);
        }
        
        if (! StringUtilities :: is_null_or_empty($object->competences_end, true))
        {
            $competence_description = new CompetenceDescription();
            $competence_description->set_type(Competence :: TYPE_END);
            $competence_description->set_description($this->convert_to_utf8($object->competences_end));
            $course->add_competence($competence_description);
        }
        
        foreach ($this->retrieve_competences($course_parameters) as $competence)
        {
            $course->add_competence($competence);
        }
        
        foreach ($this->retrieve_languages($course_parameters) as $language)
        {
            $course->add_language($language);
        }
        
        foreach ($this->retrieve_timeframe_parts($course) as $timeframe_part)
        {
            $course->add_timeframe_part($timeframe_part);
        }
        
        foreach ($this->retrieve_teachers($course_parameters) as $teacher)
        {
            $course->add_teacher($teacher);
        }
        
        if ($course->get_programme_type() == Course :: PROGRAMME_TYPE_COMPLEX)
        {
            $course->set_children($this->retrieve_children($course_parameters));
        }
        return $course;
    }

    public function retrieve_course_next_id($course)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition('previous_id', $course->get_id());
        $conditions[] = new EqualityCondition('source', $course->get_source());
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT id FROM v_discovery_course_advanced WHERE ' .
             DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->id;
        }
        else
        {
            return false;
        }
    }

    public function retrieve_children($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        $children = array();
        
        $conditions = array();
        $conditions[] = new EqualityCondition('parent_id', parent_id);
        $conditions[] = new EqualityCondition('source', $source);
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT * FROM v_discovery_course_advanced WHERE ' .
             DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
            {
                if (! isset($this->course[$result->id][$source]))
                {
                    $course_parameters->set_programme_id($result->id);
                    $this->course[$result->id][$source] = $this->result_to_course($course_parameters, $result);
                }
                $children[] = $this->course[$result->id][$source];
            }
        }
        
        return $children;
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\EvaluationStructured
     */
    public function retrieve_evaluations($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->evaluations[$programme_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_evaluation WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $evaluation = new EvaluationStructured();
                    $evaluation->set_programme_id($result->programme_id);
                    $evaluation->set_id($result->id);
                    $evaluation->set_try($result->try);
                    $evaluation->set_moment_id($result->moment_id);
                    $evaluation->set_moment($this->convert_to_utf8($result->moment));
                    $evaluation->set_type_id($result->type_id);
                    $evaluation->set_type($this->convert_to_utf8($result->type));
                    $evaluation->set_permanent($result->permanent);
                    $evaluation->set_percentage($result->percentage);
                    $evaluation->set_description($this->convert_to_utf8($result->remarks));
                    
                    $this->evaluations[$programme_id][] = $evaluation;
                }
            }
        }
        
        return $this->evaluations[$programme_id];
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\ActivityStructured
     */
    public function retrieve_activities($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->activities[$programme_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_activity WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $activity = new ActivityStructured();
                    $activity->set_programme_id($result->programme_id);
                    $activity->set_id($result->id);
                    $activity->set_group_id($result->group_id);
                    $activity->set_group($this->convert_to_utf8($result->group));
                    $activity->set_name($this->convert_to_utf8($result->name));
                    $activity->set_time($result->time);
                    $activity->set_remarks($this->convert_to_utf8($result->remarks));
                    $activity->set_description($this->convert_to_utf8($result->description));
                    
                    $this->activities[$programme_id][] = $activity;
                }
            }
        }
        
        return $this->activities[$programme_id];
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\MaterialStructured
     */
    public function retrieve_materials($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->materials[$programme_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_material WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $material = new MaterialStructured();
                    $material->set_programme_id($result->programme_id);
                    $material->set_id($result->id);
                    $material->set_group_id($result->group_id);
                    $material->set_group($this->convert_to_utf8($result->group));
                    $material->set_title($this->convert_to_utf8($result->title));
                    $material->set_author($this->convert_to_utf8($result->author));
                    $material->set_editor($this->convert_to_utf8($result->editor));
                    $material->set_edition($this->convert_to_utf8($result->edition));
                    $material->set_isbn($this->convert_to_utf8($result->isbn));
                    $material->set_medium_id($result->medium_id);
                    $material->set_medium($this->convert_to_utf8($result->medium));
                    $material->set_price($result->price);
                    $material->set_for_sale($result->for_sale);
                    $material->set_type($result->required);
                    $material->set_description($this->convert_to_utf8($result->remarks));
                    
                    $this->materials[$programme_id][] = $material;
                }
            }
        }
        
        return $this->materials[$programme_id];
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\CompetenceStructured
     */
    public function retrieve_competences($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->competences[$programme_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_competence WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $competence = new CompetenceStructured();
                    $competence->set_programme_id($result->programme_id);
                    $competence->set_id($result->id);
                    $competence->set_code($this->convert_to_utf8($result->code));
                    $competence->set_level($this->convert_to_utf8($result->level));
                    $competence->set_type($result->type);
                    $competence->set_summary($this->convert_to_utf8($result->short_description));
                    $competence->set_description($this->convert_to_utf8($result->long_description));
                    
                    $this->competences[$programme_id][] = $competence;
                }
            }
        }
        
        return $this->competences[$programme_id];
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\Language
     */
    public function retrieve_languages($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->languages[$programme_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_language WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $language = new Language();
                    $language->set_programme_id($result->programme_id);
                    $language->set_id($result->id);
                    $language->set_language_id($result->language_id);
                    $language->set_language($this->convert_to_utf8($result->language));
                    
                    $this->languages[$programme_id][] = $language;
                }
            }
        }
        
        return $this->languages[$programme_id];
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\TimeframePart
     */
    public function retrieve_timeframe_parts($course)
    {
        $timeframe_id = $course->get_timeframe_id();
        $source = $course->get_source();
        
        if (! isset($this->timeframe_parts[$timeframe_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('timeframe_id', $timeframe_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_timeframe_part WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $timeframe_part = new TimeframePart();
                    $timeframe_part->set_timeframe_id($result->timeframe_id);
                    $timeframe_part->set_id($result->id);
                    $timeframe_part->set_name($this->convert_to_utf8($result->timeframe_part));
                    $timeframe_part->set_date($result->timeframe_part_date);
                    
                    $this->timeframe_parts[$timeframe_id][] = $timeframe_part;
                }
            }
        }
        
        return $this->timeframe_parts[$timeframe_id];
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\Teacher
     */
    public function retrieve_teachers($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->course[$programme_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('source', $source);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_teaching_assignment_teacher_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $teacher = new Teacher();
                    $teacher->set_programme_id($result->programme_id);
                    $teacher->set_id($result->id);
                    $teacher->set_source($result->source);
                    $teacher->set_person_id($result->person_id);
                    $teacher->set_coordinator($result->coordinator);
                    
                    $this->teachers[$programme_id][] = $teacher;
                }
            }
        }
        
        return $this->teachers[$programme_id];
    }
}
