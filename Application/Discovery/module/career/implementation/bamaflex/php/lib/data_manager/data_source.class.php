<?php
namespace application\discovery\module\career\implementation\bamaflex;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use common\libraries\ArrayResultSet;
use user\UserDataManager;

use application\discovery\module\career\MarkMoment;
use application\discovery\module\career\Photo;
use application\discovery\module\career\Communication;
use application\discovery\module\career\Email;
use application\discovery\module\career\IdentificationCode;
use application\discovery\module\career\Name;
use application\discovery\module\career\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $contract_types = array();
    private $enrollments = array();
    private $mark_moments = array();
    private $mark = array();
    private $courses = array();
    private $child_courses = array();

    /**
     * @param int $id
     * @return multitype:int
     */
    function retrieve_contract_types($parameters)
    {
    	$id = $parameters->get_user_id();
        if (! isset($this->contract_types[$id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();

            $query = 'SELECT DISTINCT [contract_type] FROM [dbo].[v_discovery_enrollment_advanced] WHERE person_id = ' . $official_code . ' ORDER BY contract_type';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->contract_types[$id][] = $result->contract_type;
                }
            }
        }

        return $this->contract_types[$id];
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
    function retrieve_courses($parameters)
    {
    	$user_id = $parameters->get_user_id();
        if (! isset($this->courses[$user_id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();

            $child_courses = $this->retrieve_child_courses($parameters);

            $query = 'SELECT * FROM [dbo].[v_discovery_career_advanced] ';
            $query .= 'WHERE programme_parent_id IS NULL AND person_id = ' . $official_code . ' ';
            $query .= 'ORDER BY year, name';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $course = $this->result_to_course($parameters, $result);

                    if ($result->programme_id && isset($child_courses[$result->source][$result->enrollment_id][$result->programme_id]))
                    {
                        foreach ($child_courses[$result->source][$result->enrollment_id][$result->programme_id] as $child_course)
                        {
                            $course->add_child($child_course);
                        }
                    }

                    $this->courses[$user_id][] = $course;
                }
            }
        }

        return $this->courses[$user_id];
    }

    private function retrieve_child_courses($parameters)
    {
    	$user_id = $parameters->get_user_id();
        if (! isset($this->child_courses[$user_id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();

            $query = 'SELECT * FROM [dbo].[v_discovery_career_advanced] ';
            $query .= 'WHERE programme_parent_id IS NOT NULL AND person_id = ' . $official_code . ' ';
            $query .= 'ORDER BY year, trajectory_part, name';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->child_courses[$user_id][$result->source][$result->enrollment_id][$result->programme_parent_id][] = $this->result_to_course($parameters, $result);
                }
            }
        }

        return $this->child_courses[$user_id];
    }

    function result_to_course($parameters, $result)
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

        $marks = $this->retrieve_marks($parameters->get_user_id());

        foreach ($this->retrieve_mark_moments($parameters) as $moment)
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
     * @param string $user_id
     * @return multitype:\application\discovery\module\career\MarkMoment
     */
    function retrieve_mark_moments($parameters)
    {
    	$user_id = $parameters->get_user_id();
        if (! isset($this->mark_moments[$user_id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();

            $query = 'SELECT DISTINCT [try_id], [try_name], [try_order] FROM [dbo].[v_discovery_mark_advanced] ';
            $query .= 'WHERE [person_id] = ' . $official_code . ' ';
            $query .= 'ORDER BY [try_order]';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $mark_moment = new MarkMoment();
                    $mark_moment->set_id($result->try_id);
                    $mark_moment->set_name($result->try_name);

                    $this->mark_moments[$user_id][$result->try_id] = $mark_moment;
                }
            }
        }

        return $this->mark_moments[$user_id];
    }

    /**
     * @return multitype:multitype:multitype:stdClass
     */
    function retrieve_marks($user_id)
    {
        if (! isset($this->marks[$user_id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();

            $query = 'SELECT [source], [enrollment_programme_id], [result], [status], [sub_status], [try_id] FROM [dbo].[v_discovery_mark_advanced] ';
            $query .= 'WHERE [person_id] = "' . $official_code . '"';

            $statement = $this->get_connection()->prepare($query);
            $result = $statement->execute();

            if (! $result instanceof MDB2_Error)
            {
                while ($mark_result = $result->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $mark = new Mark();
                    $mark->set_moment($mark_result->try_id);
                    $mark->set_result($mark_result->result);
                    $mark->set_status($mark_result->status);
                    $mark->set_sub_status($mark_result->sub_status);
                    $mark->set_publish_status($mark_result->publish_status);

                    $this->marks[$user_id][$mark_result->source][$mark_result->enrollment_programme_id][$mark_result->try_id] = $mark;
                }
            }
        }

        return $this->marks[$user_id];
    }
}
?>