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

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $contract_types = array();

    private $enrollments = array();

    /**
     *
     * @param unknown_type $id
     * @return multitype:int
     */
    function retrieve_contract_types($parameters)
    {
        $user_id = $parameters->get_user_id();
        if (! isset($this->contract_types[$user_id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();
            
            $query = 'SELECT DISTINCT contract_type FROM v_discovery_enrollment_advanced WHERE person_id = "' . $official_code . '" ORDER BY contract_type';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->contract_types[$user_id][] = $result->contract_type;
                }
            }
        }
        
        return $this->contract_types[$user_id];
    }

    /**
     *
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
            
            $query = 'SELECT * FROM v_discovery_enrollment_advanced WHERE person_id = "' . $official_code . '" ORDER BY year DESC, id';
            
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
                    $enrollment->set_training_id($result->training_id);
                    $enrollment->set_faculty($this->convert_to_utf8($result->faculty));
                    $enrollment->set_faculty_id($result->faculty_id);
                    $enrollment->set_contract_type($result->contract_type);
                    $enrollment->set_contract_id($result->contract_id);
                    $enrollment->set_trajectory_type($result->trajectory_type);
                    $enrollment->set_trajectory($this->convert_to_utf8($result->trajectory));
                    $enrollment->set_option_choice($this->convert_to_utf8($result->option_choice));
                    $enrollment->set_graduation_option($this->convert_to_utf8($result->graduation_option));
                    $enrollment->set_result($result->result);
                    $enrollment->set_distinction($result->distinction);
                    $enrollment->set_generation_student($result->generation_student);
                    $enrollment->set_person_id($result->person_id);
                    $this->enrollments[$id][] = $enrollment;
                }
            }
        }
        
        return $this->enrollments[$id];
    }

    function count_enrollments($parameters)
    {
        $id = $parameters->get_user_id();
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        
        $query = 'SELECT count(id) AS enrollments_count FROM v_discovery_enrollment_advanced WHERE person_id = "' . $official_code . '"';
        
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        if (! $results instanceof MDB2_Error)
        {
            $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
            return $result->enrollments_count;
        }
        
        return 0;
    }
}
?>