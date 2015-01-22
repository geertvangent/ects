<?php
namespace Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\DataManager;

use Chamilo\Application\Discovery\DataSource\Bamaflex\HistoryReference;
use Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Course;
use Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Mark;
use Chamilo\Application\Discovery\Module\Career\MarkMoment;
use Chamilo\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;
use Chamilo\Application\Discovery\Module\Training\Implementation\Bamaflex\Training;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Doctrine\DBAL\Driver\PDOStatement;

class DataSource extends \Chamilo\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $contract_types = array();

    private $contract_ids = array();

    private $enrollments = array();

    private $mark_moments = array();

    private $mark = array();

    private $courses = array();

    private $child_courses = array();

    private $trainings = array();

    /**
     *
     * @param int $id
     * @return multitype:int
     */
    public function retrieve_contract_types($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->contract_types[$id]))
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT DISTINCT contract_type FROM v_discovery_enrollment_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY contract_type';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->contract_types[$id][] = $result->contract_type;
                }
            }
        }
        
        return $this->contract_types[$id];
    }

    public function retrieve_contract_ids($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->contract_ids[$id]))
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT DISTINCT contract_id FROM v_discovery_enrollment_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year DESC';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->contract_ids[$id][] = $result->contract_id;
                }
            }
        }
        
        return $this->contract_ids[$id];
    }

    public function retrieve_training($source, $training_id)
    {
        if (! isset($this->trainings[$source][$training_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('id'), 
                new StaticConditionVariable($training_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_training_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $training = new Training();
                    $training->set_source($result->source);
                    $training->set_id($result->id);
                    $training->set_name($this->convert_to_utf8($result->name));
                    $training->set_year($this->convert_to_utf8($result->year));
                    $training->set_credits($result->credits);
                    $training->set_domain_id($result->domain_id);
                    $training->set_domain($this->convert_to_utf8($result->domain));
                    $training->set_goals(nl2br($this->convert_to_utf8($result->goals)));
                    $training->set_type_id($result->type_id);
                    $training->set_type($this->convert_to_utf8($result->type));
                    $training->set_bama_type($result->bama_type);
                    $training->set_faculty_id($result->faculty_id);
                    $training->set_start_date($result->start_date);
                    $training->set_end_date($result->end_date);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($result->previous_id);
                    $reference->set_source($result->previous_source);
                    $training->add_previous_reference($reference);
                    
                    $next = $this->retrieve_training_next_id($training);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($next->id);
                    $reference->set_source($next->source);
                    $training->add_next_reference($reference);
                    
                    $this->trainings[$source][$training_id] = $training;
                }
            }
        }
        
        return $this->trainings[$source][$training_id];
    }

    public function retrieve_training_next_id($training)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('previous_id'), 
            new StaticConditionVariable($training->get_id()));
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('source'), 
            new StaticConditionVariable($training->get_source()));
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT id, source FROM v_discovery_training_advanced WHERE ' .
             ConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            return $statement->fetch(\PDO :: FETCH_OBJ);
        }
        else
        {
            return false;
        }
    }

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Enrollment
     */
    public function retrieve_enrollments($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->enrollments[$id]))
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT * FROM v_discovery_enrollment_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year DESC, id';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $enrollment = new Enrollment();
                    $enrollment->set_instance($this->get_module_instance());
                    $enrollment->set_source($result->source);
                    $enrollment->set_id($result->id);
                    $enrollment->set_year($this->convert_to_utf8($result->year));
                    $enrollment->set_training($this->convert_to_utf8($result->training));
                    $enrollment->set_training_id($this->convert_to_utf8($result->training_id));
                    $enrollment->set_faculty($this->convert_to_utf8($result->faculty));
                    $enrollment->set_contract_type($result->contract_type);
                    $enrollment->set_contract_id($result->contract_id);
                    $enrollment->set_trajectory_type($result->trajectory_type);
                    $enrollment->set_trajectory($this->convert_to_utf8($result->trajectory));
                    $enrollment->set_option_choice($this->convert_to_utf8($result->option_choice));
                    $enrollment->set_graduation_option($this->convert_to_utf8($result->graduation_option));
                    $enrollment->set_result($result->result);
                    
                    $this->enrollments[$id][] = $enrollment;
                }
            }
        }
        
        return $this->enrollments[$id];
    }

    /**
     *
     * @param int $user_id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    public function retrieve_courses($enrollment_ids)
    {
        $enrollment_id = md5(serialize($enrollment_ids));
        
        if (! isset($this->courses[$enrollment_id]))
        {
            $child_courses = $this->retrieve_child_courses($enrollment_ids);
            
            $conditions = array();
            $conditions[] = new EqualityCondition(new StaticColumnConditionVariable('programme_parent_id'), null);
            $conditions[] = new InCondition(new StaticColumnConditionVariable('enrollment_id'), $enrollment_ids);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_career_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year, name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $course = $this->result_to_course($enrollment_ids, $result);
                    
                    if ($result->programme_id &&
                         isset($child_courses[$result->source][$result->enrollment_id][$result->programme_id]))
                    {
                        foreach ($child_courses[$result->source][$result->enrollment_id][$result->programme_id] as $child_course)
                        {
                            $course->add_child($child_course);
                        }
                    }
                    
                    $this->courses[$enrollment_id][$course->get_enrollment_id()][] = $course;
                }
            }
        }
        
        return $this->courses[$enrollment_id];
    }

    public function count_courses($parameters)
    {
        $user_id = $parameters->get_user_id();
        $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($user_id);
        $official_code = $user->get_official_code();
        
        $condition = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'), 
            new StaticConditionVariable($official_code));
        
        $query = 'SELECT count(id) AS courses_count FROM v_discovery_career_advanced WHERE ' .
             ConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->courses_count;
        }
        return 0;
    }

    private function retrieve_child_courses($enrollment_ids)
    {
        $enrollment_id = md5(serialize($enrollment_ids));
        
        if (! isset($this->child_courses[$enrollment_id]))
        {
            $conditions = array();
            $conditions[] = new NotCondition(
                new EqualityCondition(new StaticColumnConditionVariable('programme_parent_id'), null));
            $conditions[] = new InCondition(new StaticColumnConditionVariable('enrollment_id'), $enrollment_ids);
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_career_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY year, trajectory_part, name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->child_courses[$enrollment_id][$result->source][$result->enrollment_id][$result->programme_parent_id][] = $this->result_to_course(
                        $enrollment_id, 
                        $result);
                }
            }
        }
        
        return $this->child_courses[$enrollment_id];
    }

    public function result_to_course($enrollment_ids, $result)
    {
        $course = new Course();
        $course->set_id($result->id);
        $course->set_programme_id($result->programme_id);
        $course->set_parent_programme_id($result->parent_programme_id);
        $course->set_type($result->type);
        $course->set_enrollment_id($result->enrollment_id);
        $course->set_year($this->convert_to_utf8($result->year));
        $course->set_name($this->convert_to_utf8($result->name));
        $course->set_trajectory_part($result->trajectory_part);
        $course->set_credits($result->credits);
        $course->set_weight($result->weight);
        $course->set_source($result->source);
        
        $marks = $this->retrieve_marks($enrollment_ids);
        
        foreach ($this->retrieve_mark_moments($enrollment_ids) as $moment)
        {
            if (isset($marks[$result->source][$result->id][$moment->get_id()]))
            {
                $mark = $marks[$result->source][$result->id][$moment->get_id()];
                $course->add_mark($mark);
            }
            else
            {
                $mark = Mark :: factory($moment->get_id());
            }
            
            $course->add_mark($mark);
        }
        
        return $course;
    }

    /**
     *
     * @param string $user_id
     * @return multitype:\application\discovery\module\career\MarkMoment
     */
    public function retrieve_mark_moments($enrollment_ids)
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
        
        // $user_id = $parameters->get_user_id();
        // if (! isset($this->mark_moments[$user_id]))
        // {
        // $user = \core\user\DataManager :: get_instance()->retrieve_user($user_id);
        // $official_code = $user->get_official_code();
        
        // $condition = new EqualityCondition(new StaticColumnConditionVariable('person_id'), new
        // StaticConditionVariable($official_code));
        //
        
        // $query = 'SELECT DISTINCT try_id, try_name, try_order FROM v_discovery_mark_advanced ' .
        // $translator->render_query($condition) . ' ORDER BY try_order';
        
        // $statement = $this->get_connection()->query($query);
        
        // if ($statement instanceof PDOStatement)
        // {
        // while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
        // {
        // $mark_moment = new MarkMoment();
        // $mark_moment->set_id($result->try_id);
        // $mark_moment->set_name($result->try_name);
        
        // $this->mark_moments[$user_id][$result->try_id] = $mark_moment;
        // }
        // }
        // }
        
        // return $this->mark_moments[$user_id];
    }

    /**
     *
     * @return multitype:multitype:multitype:stdClass
     */
    public function retrieve_marks($enrollment_ids)
    {
        $enrollment_id = md5(serialize($enrollment_ids));
        if (! isset($this->marks[$enrollment_id]))
        {
            $condition = new InCondition(new StaticColumnConditionVariable('enrollment_id'), $enrollment_ids);
            
            $query = 'SELECT * FROM v_discovery_mark_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection());
            
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
                    
                    $this->marks[$enrollment_id][$result->source][$result->enrollment_programme_id][$result->try_id] = $mark;
                }
            }
        }
        
        return $this->marks[$enrollment_id];
    }
}
