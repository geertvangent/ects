<?php
namespace application\discovery\module\career\implementation\bamaflex;

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

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\career\implementation\bamaflex\Career
     */
    function retrieve_root_courses($id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $query = 'SELECT * FROM [dbo].[v_discovery_career_basic] WHERE parent_id IS NULL AND person_id = ' . $official_code;

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        $courses = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $course = new Course();
                $course->set_id($result->id);
                $course->set_year($this->convert_to_utf8($result->year));
                $course->set_name($this->convert_to_utf8($result->name));
                $course->set_trajectory_part($result->trajectory_part);
                $course->set_credits($result->credits);
                $course->set_weight($result->weight);
                $courses[] = $course;
            }
        }

        return $courses;
    }
}
?>