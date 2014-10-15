<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

use Doctrine\DBAL\Driver\PDOStatement;
use libraries\storage\DoctrineConditionTranslator;
use libraries\storage\AndCondition;
use libraries\storage\EqualityCondition;
use application\discovery\module\course\implementation\bamaflex\Course;
use application\discovery\module\career\MarkMoment;
use application\discovery\module\career\implementation\bamaflex\Mark;
use stdClass;
use libraries\storage\StaticColumnConditionVariable;
use libraries\storage\StaticConditionVariable;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource
{

    private $mark_moments = array();

    private $mark = array();

    private $course_results = array();

    private $course;

    /**
     *
     * @param int $programme_id
     * @return multitype:\application\discovery\module\course_result\implementation\bamaflex\CourseResult
     */
    public function retrieve_course_results($course_results_parameters)
    {
        $programme_id = $course_results_parameters->get_programme_id();
        $source = $course_results_parameters->get_source();
        
        if (! isset($this->course_results[$programme_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('programme_id'), 
                new StaticConditionVariable($programme_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_results_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY person_last_name, person_first_name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->course_results[$programme_id][$source][] = $this->result_to_course_result(
                        $course_results_parameters, 
                        $result);
                }
            }
        }
        
        return $this->course_results[$programme_id][$source];
    }

    public function retrieve_course($course_parameters)
    {
        $programme_id = $course_parameters->get_programme_id();
        $source = $course_parameters->get_source();
        
        if (! isset($this->course[$programme_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('id'), 
                new StaticConditionVariable($programme_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_course_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                $result = $statement->fetch(\PDO :: FETCH_OBJ);
                if ($result instanceof stdClass)
                {
                    $this->course[$programme_id][$source] = $this->result_to_course($result);
                }
            }
        }
        return $this->course[$programme_id][$source];
    }

    public function result_to_course($object)
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
        $course->set_previous_id($object->previous_id);
        $course->set_previous_parent_id($object->previous_parent_id);
        $course->set_next_id($this->retrieve_course_next_id($course));
        
        return $course;
    }

    public function retrieve_course_next_id($course)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('previous_id'), 
            new StaticConditionVariable($course->get_id()));
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('source'), 
            new StaticConditionVariable($course->get_source()));
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT id FROM v_discovery_course_advanced WHERE ' .
             DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->id;
        }
        else
        {
            return false;
        }
    }

    public function result_to_course_result($course_results_parameters, $result)
    {
        $course_result = new CourseResult();
        $course_result->set_id($result->id);
        $course_result->set_type($result->type);
        $course_result->set_person_last_name($this->convert_to_utf8($result->person_last_name));
        $course_result->set_person_first_name($this->convert_to_utf8($result->person_first_name));
        $course_result->set_person_id($result->person_id);
        $course_result->set_trajectory_type($result->trajectory_type);
        
        $marks = $this->retrieve_marks(
            $course_results_parameters->get_programme_id(), 
            $course_results_parameters->get_source());
        
        foreach ($this->retrieve_mark_moments($course_results_parameters) as $moment)
        {
            if (isset($marks[$result->source][$result->id][$moment->get_id()]))
            {
                $mark = $marks[$result->source][$result->id][$moment->get_id()];
                $course_result->add_mark($mark);
            }
            else
            {
                $mark = Mark :: factory($moment->get_id());
            }
            
            $course_result->add_mark($mark);
        }
        
        return $course_result;
    }

    /**
     *
     * @param string $user$programme_id
     * @return multitype:\application\discovery\module\course_results\MarkMoment
     */
    public function retrieve_mark_moments($course_results_parameters)
    {
        $moments = array();
        
        $mark_moment = new MarkMoment();
        $mark_moment->set_id(1);
        $mark_moment->set_name('1<sup>ste</sup> kans');
        $moments[1] = $mark_moment;
        
        $mark_moment = new MarkMoment();
        $mark_moment->set_id(2);
        $mark_moment->set_name('2<sup>de</sup> kans');
        $moments[2] = $mark_moment;
        
        return $moments;
        
        // $programme_id = $course_results_parameters->get_programme_id();
        // $source = $course_results_parameters->get_source();
        
        // if (! isset($this->mark_moments[$programme_id][$source]))
        // {
        // $query = 'SELECT DISTINCT try_id, try_name, try_order FROM v_discovery_mark_advanced ';
        // $query .= 'WHERE programme_id = "' . $programme_id . '" AND source = ' . $source . ' ';
        // $query .= 'ORDER BY try_order';
        
        // $statement = $this->get_connection()->prepare($query);
        // $results = $statement->execute();
        
        // if (! $results instanceof MDB2_Error)
        // {
        // while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
        // {
        // $mark_moment = new MarkMoment();
        // $mark_moment->set_id($result->try_id);
        // $mark_moment->set_name($result->try_name);
        
        // $this->mark_moments[$programme_id][$source][$result->try_id] = $mark_moment;
        // }
        // }
        // }
        
        // return $this->mark_moments[$programme_id][$source];
    }

    /**
     *
     * @return multitype:multitype:multitype:stdClass
     */
    public function retrieve_marks($programme_id, $source)
    {
        if (! isset($this->marks[$programme_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('programme_id'), 
                new StaticConditionVariable($programme_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_mark_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $mark = new Mark();
                    $mark->set_moment($result->try_id);
                    $mark->set_result($result->result);
                    $mark->set_status($result->status);
                    $mark->set_sub_status($result->sub_status);
                    $mark->set_publish_status($result->publish_status);
                    $mark->set_abandoned($result->abandoned);
                    
                    $this->marks[$programme_id][$source][$result->source][$result->enrollment_programme_id][$result->try_id] = $mark;
                }
            }
        }
        
        return $this->marks[$programme_id][$source];
    }
}
