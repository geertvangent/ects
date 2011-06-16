<?php
namespace application\discovery\module\career\implementation\bamaflex;

use application\discovery\module\career\Mark;

use application\discovery\module\career\MarkMoment;

use user\UserDataManager;

use application\discovery\module\career\Photo;
use application\discovery\module\career\Communication;
use application\discovery\module\career\Email;
use application\discovery\module\career\IdentificationCode;
use application\discovery\module\career\Name;
use application\discovery\module\career\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $mark_moments;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    function retrieve_courses($id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $query = 'SELECT * FROM [dbo].[v_discovery_career_basic] ';
        $query .= 'WHERE programme_parent_id IS NULL AND person_id = ' . $official_code . ' ';
        $query .= 'ORDER BY year, trajectory_part, name';

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        $this->mark_moments = $this->retrieve_mark_moments($id);

        $courses = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $course = $this->result_to_course($result);

                //Check whether the course has children, if so: add them.
                $query = 'SELECT * FROM [dbo].[v_discovery_career_basic] ';
                $query .= 'WHERE programme_parent_id = ' . $result->programme_id . ' AND person_id = ' . $result->person_id . ' ';
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

    function result_to_course($result)
    {
        $course = new Course();
        $course->set_id($result->id);
        $course->set_year($this->convert_to_utf8($result->year));
        $course->set_name($this->convert_to_utf8($result->name));
        $course->set_trajectory_part($result->trajectory_part);
        $course->set_credits($result->credits);
        $course->set_weight($result->weight);

        foreach($this->mark_moments as $moment)
        {
            $course->add_mark($this->retrieve_mark($result->id, $moment->get_id()));
        }

        return $course;
    }

    /**
     * @param string $user_id
     * @return multitype:\application\discovery\module\career\MarkMoment
     */
    function retrieve_mark_moments($user_id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($user_id);
        $official_code = $user->get_official_code();

        $query = 'SELECT DISTINCT [moment_id], [moment_name], [moment_order] FROM [dbo].[v_discovery_mark_basic] ';
        $query .= 'WHERE [person_id] = ' . $official_code . ' ';
        $query .= 'ORDER BY [moment_order]';

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        $mark_moments = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $mark_moment = new MarkMoment();
                $mark_moment->set_id($result->moment_id);
                $mark_moment->set_name($result->moment_name);

                $mark_moments[$result->moment_id] = $mark_moment;
            }
        }

        return $mark_moments;
    }

    /**
     * @param string $user_id
     * @return multitype:\application\discovery\module\career\Mark
     */
    function retrieve_mark($course_id, $moment_id)
    {
        $query = 'SELECT [result], [status_code], [moment_id] FROM [dbo].[v_discovery_mark_basic] ';
        $query .= 'WHERE [enrollment_programme_id] = ' . $course_id . ' AND [moment_id] = ' . $moment_id . ' ';

        $statement = $this->get_connection()->prepare($query);
        $result = $statement->execute();

        $mark_moments = array();

        if (! $result instanceof MDB2_Error)
        {
            $result = $result->fetchRow(MDB2_FETCHMODE_OBJECT);

            $mark = new Mark();
            $mark->set_moment($result->moment_id);
            $mark->set_result($result->result);
            $mark->set_status($result->status_code);

            return $mark;
        }
        else
        {
            return Mark :: factory($moment_id);
        }
    }
}
?>