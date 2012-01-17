<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

use application\discovery\module\career\implementation\bamaflex\Course;
use application\discovery\DiscoveryItem;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use common\libraries\ArrayResultSet;
use user\UserDataManager;

use application\discovery\module\student_materials\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $years = array();
    private $enrollments = array();
    private $courses = array();
    private $child_courses = array();
    private $materials = array();

    /**
     * @param int $id
     * @return multitype:string
     */
    function retrieve_years($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->years[$id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $query = 'SELECT DISTINCT [year] FROM [dbo].[v_discovery_enrollment_advanced] WHERE person_id = ' . $official_code . ' ORDER BY year DESC';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->years[$id][] = $result->year;
                }
            }
        }
        
        return $this->years[$id];
    }

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Enrollment
     */
    function retrieve_enrollments($parameters)
    {
        $id = $parameters->get_user_id();
        
        if (! isset($this->enrollments[$id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $query = 'SELECT * FROM [dbo].[v_discovery_enrollment_advanced] WHERE person_id = ' . $official_code . ' ORDER BY year DESC, id';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
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
     * @param int $user_id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    function retrieve_courses($enrollment_id)
    {
        if (! isset($this->courses[$enrollment_id]))
        {
            $child_courses = $this->retrieve_child_courses($enrollment_id);
            
            $query = 'SELECT * FROM [dbo].[v_discovery_career_advanced] ';
            $query .= 'WHERE programme_parent_id IS NULL AND enrollment_id = ' . $enrollment_id . ' ';
            $query .= 'ORDER BY year, name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $course = $this->result_to_course($result);
                    
                    if ($result->programme_id && isset($child_courses[$result->source][$result->enrollment_id][$result->programme_id]))
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
            $query = 'SELECT * FROM [dbo].[v_discovery_career_advanced] ';
            $query .= 'WHERE programme_parent_id IS NOT NULL AND enrollment_id = ' . $enrollment_id . ' ';
            $query .= 'ORDER BY year, trajectory_part, name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->child_courses[$enrollment_id][$result->source][$result->enrollment_id][$result->programme_parent_id][] = $this->result_to_course($result);
                }
            }
        }
        
        return $this->child_courses[$enrollment_id];
    }

    function result_to_course($result)
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
     * @param Parameter $course_parameters
     * @return multitype:\application\discovery\module\course\implementation\bamaflex\MaterialStructured
     */
    function retrieve_materials($programme_id, $type)
    {
        
        if (! isset($this->materials[$programme_id][$type]))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_course_material] ';
            $query .= 'WHERE programme_id = "' . $programme_id . '" AND required = "' . $type . '"';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
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

    function count_materials($parameters = null, $year = null, $enrollment_id = null, $type = null)
    {
        $id = $parameters->get_user_id();
        
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        if (! $enrollment_id)
        {
            $query = 'SELECT DISTINCT id FROM [dbo].[v_discovery_enrollment_advanced] WHERE person_id = "' . $official_code . '"';
            if ($year)
            {
                $query .= ' AND year = "' . $year . '"';
            }
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            $enrollments_ids = array();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
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
            $query = 'SELECT DISTINCT programme_id FROM [dbo].[v_discovery_career_advanced] ';
            $query .= 'WHERE enrollment_id IN ("' . implode('","', $enrollments_ids) . '")';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            $course_ids = array();
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $course_ids[] = $result->programme_id;
                }
            }
            
            if (count($course_ids) > 0)
            {
                $query = 'SELECT count(id) AS materials_count FROM [dbo].[v_discovery_course_material] ';
                $query .= 'WHERE programme_id IN("' . implode('","', $course_ids) . '")';
                if (! is_null($type))
                {
                	$query .= ' AND required = "' . $type . '"';
                }
                $statement = $this->get_connection()->prepare($query);
                $results = $statement->execute();
                
                if (! $results instanceof MDB2_Error)
                {
                    $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
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
?>