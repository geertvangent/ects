<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

use common\libraries\OrCondition;
use common\libraries\InCondition;
use common\libraries\NotCondition;
use common\libraries\AndCondition;
use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\DoctrineConditionTranslator;
use common\libraries\EqualityCondition;
use application\discovery\module\career\implementation\bamaflex\Course;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use user\UserDataManager;
use application\discovery\module\student_materials\DataManagerInterface;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
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
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition('person_id', $official_code);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT DISTINCT year FROM v_discovery_enrollment_advanced ' . $translator->render_query(
                    $condition) . ' ORDER BY year DESC';
            
            $statement = $this->query($query);
            
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
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition('person_id', $official_code);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_enrollment_advanced ' . $translator->render_query($condition) . ' ORDER BY year DESC, id';
            
            $statement = $this->query($query);
            
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
            $conditions[] = new EqualityCondition('programme_parent_id', null);
            $conditions[] = new EqualityCondition('enrollment_id', $enrollment_id);
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_career_advanced ' . $translator->render_query($condition) . ' ORDER BY year, name';
            
            $statement = $this->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $course = $this->result_to_course($result);
                    
                    if ($result->programme_id && isset(
                            $child_courses[$result->source][$result->enrollment_id][$result->programme_id]))
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
            $conditions[] = new NotCondition(new EqualityCondition('programme_parent_id', null));
            $conditions[] = new EqualityCondition('enrollment_id', $enrollment_id);
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_career_advanced ' . $translator->render_query($condition) . ' ORDER BY year, trajectory_part, name';
            
            $statement = $this->query($query);
            
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
            $conditions[] = new EqualityCondition('programme_id', $programme_id);
            $conditions[] = new EqualityCondition('required', $type);
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_course_material ' . $translator->render_query($condition);
            
            $statement = $this->query($query);
            
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
        
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        if (! $enrollment_id)
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('person_id', $official_code);
            
            if ($year)
            {
                $conditions[] = new EqualityCondition('year', $year);
            }
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            $query = 'SELECT DISTINCT id FROM v_discovery_enrollment_advanced ' . $translator->render_query($condition);
            $enrollments_ids = array();
            
            $statement = $this->query($query);
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
            
            // $condition = new InCondition('enrollment_id', implode(',', $enrollments_ids));
            $conditions = array();
            foreach ($enrollments_ids as $enrollments_id)
            {
                $conditions[] = new EqualityCondition('enrollment_id', $enrollments_id);
            }
            $condition = new OrCondition($conditions);
            
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT DISTINCT programme_id FROM v_discovery_career_advanced ' . $translator->render_query(
                    $condition);
            // $query .= 'WHERE enrollment_id IN ("' . implode('","', $enrollments_ids) . '")';
            
            $course_ids = array();
            $statement = $this->query($query);
            
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
                foreach ($enrollments_ids as $enrollments_id)
                {
                    $programme_conditions[] = new EqualityCondition('programme_id', $enrollments_id);
                }
                $conditions[] = new OrCondition($programme_conditions);
                
                // $conditions[] = new InCondition('programme_id', implode(',', $course_ids));
                
                // $query .= 'WHERE programme_id IN("' . implode('","', $course_ids) . '")';
                
                if (! is_null($type))
                {
                    $conditions[] = new EqualityCondition('required', $type);
                }
                
                $condition = new AndCondition($conditions);
                $translator = DoctrineConditionTranslator :: factory($this);
                
                $query = 'SELECT count(id) AS materials_count FROM v_discovery_course_material ' . $translator->render_query(
                        $condition);
                
                $statement = $this->query($query);
                
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
