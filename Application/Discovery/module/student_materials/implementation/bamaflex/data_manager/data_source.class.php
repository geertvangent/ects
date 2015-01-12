<?php
namespace Application\Discovery\module\student_materials\implementation\bamaflex\data_manager;

use libraries\storage\OrCondition;
use libraries\storage\NotCondition;
use libraries\storage\AndCondition;
use Doctrine\DBAL\Driver\PDOStatement;
use libraries\storage\DoctrineConditionTranslator;
use libraries\storage\EqualityCondition;
use application\discovery\module\career\implementation\bamaflex\Course;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use libraries\storage\StaticColumnConditionVariable;
use libraries\storage\StaticConditionVariable;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource
{

    private $years = array();

    private $enrollments = array();

    private $courses = array();

    private $child_courses = array();

    private $materials = array();

    /**
     *
     * @param int $id
     * @return multitype:string
     */
    public function retrieve_years($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->years[$id]))
        {
            $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT DISTINCT year FROM v_discovery_enrollment_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year DESC';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->years[$id][] = $result->year;
                }
            }
        }
        
        return $this->years[$id];
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
            $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT * FROM v_discovery_enrollment_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY year DESC, id';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $enrollment = new Enrollment();
                    $enrollment->set_source($result->source);
                    $enrollment->set_id($result->id);
                    $enrollment->set_year($this->convert_to_utf8($result->year));
                    $enrollment->set_training($this->convert_to_utf8($result->training));
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
    public function retrieve_courses($enrollment_id)
    {
        if (! isset($this->courses[$enrollment_id]))
        {
            $child_courses = $this->retrieve_child_courses($enrollment_id);
            
            $conditions = array();
            $conditions[] = new EqualityCondition(new StaticColumnConditionVariable('programme_parent_id'), null);
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('enrollment_id'), 
                new StaticConditionVariable($enrollment_id));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_career_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY year, name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $course = $this->result_to_course($result);
                    
                    if ($result->programme_id &&
                         isset($child_courses[$result->source][$result->enrollment_id][$result->programme_id]))
                    {
                        foreach ($child_courses[$result->source][$result->enrollment_id][$result->programme_id] as $child_course)
                        {
                            $course->add_child($child_course);
                        }
                    }
                    
                    $this->courses[$enrollment_id][] = $course;
                }
            }
        }
        
        return $this->courses[$enrollment_id];
    }

    private function retrieve_child_courses($enrollment_id)
    {
        if (! isset($this->child_courses[$enrollment_id]))
        {
            $conditions = array();
            $conditions[] = new NotCondition(
                new EqualityCondition(new StaticColumnConditionVariable('programme_parent_id'), null));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('enrollment_id'), 
                new StaticConditionVariable($enrollment_id));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_career_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY year, trajectory_part, name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->child_courses[$enrollment_id][$result->source][$result->enrollment_id][$result->programme_parent_id][] = $this->result_to_course(
                        $result);
                }
            }
        }
        
        return $this->child_courses[$enrollment_id];
    }

    public function result_to_course($result)
    {
        $course = new Course();
        $course->set_id($result->id);
        $course->set_source($result->source);
        $course->set_programme_id($result->programme_id);
        $course->set_type($result->type);
        $course->set_enrollment_id($result->enrollment_id);
        $course->set_year($this->convert_to_utf8($result->year));
        $course->set_name($this->convert_to_utf8($result->name));
        $course->set_trajectory_part($result->trajectory_part);
        $course->set_credits($result->credits);
        $course->set_weight($result->weight);
        
        return $course;
    }

    /**
     *
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\MaterialStructured
     */
    public function retrieve_materials($programme_id, $type)
    {
        if (! isset($this->materials[$programme_id][$type]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('programme_id'), 
                new StaticConditionVariable($programme_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('required'), 
                new StaticConditionVariable($type));
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
                    
                    $this->materials[$programme_id][$type][] = $material;
                }
            }
        }
        
        return $this->materials[$programme_id][$type];
    }

    public function count_materials($parameters = null, $year = null, $enrollment_id = null, $type = null)
    {
        $id = $parameters->get_user_id();
        
        $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        if (! $enrollment_id)
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            if ($year)
            {
                $conditions[] = new EqualityCondition(
                    new StaticColumnConditionVariable('year'), 
                    new StaticConditionVariable($year));
            }
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT DISTINCT id FROM v_discovery_enrollment_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            $enrollments_ids = array();
            
            $statement = $this->get_connection()->query($query);
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $enrollments_ids[] = $result->id;
                }
            }
        }
        else
        {
            $enrollments_ids = array($enrollment_id);
        }
        
        if (count($enrollments_ids) > 0)
        {
            $conditions = array();
            foreach ($enrollments_ids as $enrollments_id)
            {
                $conditions[] = new EqualityCondition(
                    new StaticColumnConditionVariable('enrollment_id'), 
                    new StaticConditionVariable($enrollments_id));
            }
            $condition = new OrCondition($conditions);
            
            $query = 'SELECT DISTINCT programme_id FROM v_discovery_career_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $course_ids = array();
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $course_ids[] = $result->programme_id;
                }
            }
            
            if (count($course_ids) > 0)
            {
                $conditions = array();
                
                $programme_conditions = array();
                foreach ($course_ids as $course_id)
                {
                    $programme_conditions[] = new EqualityCondition(
                        new StaticColumnConditionVariable('programme_id'), 
                        new StaticConditionVariable($course_id));
                }
                $conditions[] = new OrCondition($programme_conditions);
                
                if (! is_null($type))
                {
                    $conditions[] = new EqualityCondition(
                        new StaticColumnConditionVariable('required'), 
                        new StaticConditionVariable($type));
                }
                
                $condition = new AndCondition($conditions);
                
                $query = 'SELECT count(id) AS materials_count FROM v_discovery_course_material WHERE ' .
                     DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
                
                $statement = $this->get_connection()->query($query);
                
                if ($statement instanceof PDOStatement)
                {
                    $result = $statement->fetch(\PDO :: FETCH_OBJ);
                    return $result->materials_count;
                }
            }
        }
        else
        {
            return 0;
        }
    }
}
