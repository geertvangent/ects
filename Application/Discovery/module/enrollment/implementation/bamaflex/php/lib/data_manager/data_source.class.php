<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\enrollment\Photo;
use application\discovery\module\enrollment\Communication;
use application\discovery\module\enrollment\Email;
use application\discovery\module\enrollment\IdentificationCode;
use application\discovery\module\enrollment\Name;
use application\discovery\module\enrollment\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Enrollment
     */
    function retrieve_enrollments($id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $query = 'SELECT * FROM [dbo].[v_discovery_enrollment_basic] WHERE person_id = ' . $official_code . ' --ORDER BY year DESC';

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        $enrollments = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $enrollment = new Enrollment();
                $enrollment->set_id($result->id);
                $enrollment->set_year($this->convert_to_utf8($result->year));
                $enrollment->set_training($this->convert_to_utf8($result->training));
                $enrollment->set_faculty($this->convert_to_utf8($result->faculty));
                $enrollment->set_contract_type($result->contract_type);
                $enrollment->set_trajectory_type($result->trajectory_type);
                $enrollment->set_trajectory($this->convert_to_utf8($result->trajectory));
                $enrollment->set_option_choice($this->convert_to_utf8($result->option_choice));
                $enrollment->set_graduation_option($this->convert_to_utf8($result->graduation_option));
                $enrollments[] = $enrollment;
            }
        }

        return $enrollments;
    }

    /**
     * @param Enrollment $enrollment
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    function retrieve_courses(\application\discovery\module\enrollment\Enrollment $enrollment, $id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $query = 'SELECT * FROM [dbo].[v_discovery_career_basic] ';
        $query .= 'WHERE programme_parent_id IS NULL AND person_id = ' . $official_code . ' AND enrollment_id = ' . $enrollment->get_id() . ' ';
        $query .= 'ORDER BY year, trajectory_part, name';

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        $courses = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $course = $this->result_to_course($result);

                //Check whether the course has children, if so: add them.
                $query = 'SELECT * FROM [dbo].[v_discovery_career_basic] ';
                $query .= 'WHERE programme_parent_id = ' . $result->programme_id . ' AND person_id = ' . $result->person_id . ' AND enrollment_id = ' . $enrollment->get_id() . ' ';
                $query .= 'ORDER BY year, trajectory_part, name';

                $statement = $this->get_connection()->prepare($query);
                $child_results = $statement->execute();

                if (! $child_results instanceof MDB2_Error)
                {
                    while ($child_result = $child_results->fetchRow(MDB2_FETCHMODE_OBJECT))
                    {
                        $child_course = $this->result_to_course($child_result);
                        $course->add_child($child_course);
                    }
                }

                $courses[] = $course;
            }
        }

        return $courses;
    }

    /**
     * @param stdObject $result
     * @return \application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    function result_to_course($result)
    {
        $course = new Course();
        $course->set_id($result->id);
        $course->set_year($this->convert_to_utf8($result->year));
        $course->set_name($this->convert_to_utf8($result->name));
        $course->set_trajectory_part($result->trajectory_part);
        $course->set_credits($result->credits);
        $course->set_weight($result->weight);

        return $course;
    }
}
?>