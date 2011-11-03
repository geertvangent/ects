<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\teaching_assignment\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $teaching_assignments;
    private $years;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\teaching_assignment\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_teaching_assignments($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = UserDataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        
        if (! isset($this->teaching_assignments[$person_id]))
        {
            //                     $official_code = $user->get_official_code();
            

            $query = 'SELECT * FROM [dbo].[v_discovery_teaching_assignment_advanced] WHERE person_id = ' . $person_id . ' ORDER BY year DESC, faculty, training, name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $teaching_assignment = new TeachingAssignment();
                    $teaching_assignment->set_source($result->source);
                    $teaching_assignment->set_type($result->type);
                    $teaching_assignment->set_id($result->id);
                    $teaching_assignment->set_programme_id($result->programme_id);
                    $teaching_assignment->set_name($this->convert_to_utf8($result->name));
                    $teaching_assignment->set_year($this->convert_to_utf8($result->year));
                    $teaching_assignment->set_training($this->convert_to_utf8($result->training));
                    $teaching_assignment->set_faculty($this->convert_to_utf8($result->faculty));
                    $teaching_assignment->set_training_id($result->training_id);
                    $teaching_assignment->set_faculty_id($result->faculty_id);
                    $teaching_assignment->set_trajectory_part($result->trajectory_part);
                    $teaching_assignment->set_credits($result->credits);
                    $teaching_assignment->set_weight($result->weight);
                    $teaching_assignment->set_timeframe_id($result->timeframe_id);
                    $this->teaching_assignments[$person_id][] = $teaching_assignment;
                }
            }
        }
        
        return $this->teaching_assignments[$person_id];
    }

    function retrieve_years($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = UserDataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        if (! isset($this->years[$person_id]))
        {
            //            $official_code = $user->get_official_code();
            

            $query = 'SELECT DISTINCT [year] FROM [dbo].[v_discovery_teaching_assignment_advanced] WHERE person_id = ' . $person_id . ' ORDER BY year DESC';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->years[$person_id][] = $result->year;
                }
            }
        }
        
        return $this->years[$person_id];
    }
}
?>