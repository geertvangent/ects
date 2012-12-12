<?php
namespace application\discovery\module\advice\implementation\bamaflex;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use user\UserDataManager;
use application\discovery\module\advice\DataManagerInterface;
use MDB2_Error;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $advices;

    private $enrollments;

    /**
     *
     * @param $id int
     * @return multitype:\application\discovery\module\advice\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_advices($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = UserDataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        
        if (! isset($this->advices[$person_id]))
        {
            $query = 'SELECT * FROM v_discovery_advice_basic
            			WHERE person_id = "' . $person_id . '"
            				AND (motivation IS NOT NULL OR
		            			ombudsman IS NOT NULL OR
		            			vote IS NOT NULL OR
		            			measures IS NOT NULL OR
		            			advice IS NOT NULL)
            			ORDER BY year DESC';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $advice = new Advice();
                    $advice->set_id($result->id);
                    $advice->set_enrollment_id($result->enrollment_id);
                    $advice->set_year($result->year);
                    $advice->set_person_id($result->person_id);
                    $advice->set_motivation($this->convert_to_utf8($result->motivation));
                    $advice->set_ombudsman($this->convert_to_utf8($result->ombudsman));
                    $advice->set_vote($this->convert_to_utf8($result->vote));
                    $advice->set_measures_visible($result->measures_visible);
                    $advice->set_measures($this->convert_to_utf8($result->measures));
                    $advice->set_advice_visible($result->advice_visible);
                    $advice->set_advice($this->convert_to_utf8($result->advice));
                    $advice->set_measures_valid($result->measures_valid);
                    $advice->set_date(strtotime($result->date));
                    $advice->set_try($result->try);
                    $advice->set_decision_type_id($result->decision_type_id);
                    $advice->set_decision_type($this->convert_to_utf8($result->decision_type));
                    
                    $this->advices[$person_id][] = $advice;
                }
            }
        }
        
        return $this->advices[$person_id];
    }

    function count_advices($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = UserDataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        
        $query = 'SELECT count(id) AS advices_count FROM v_discovery_advice_basic
            			WHERE person_id = "' . $person_id . '"
            				AND (motivation IS NOT NULL OR
		            			ombudsman IS NOT NULL OR
		            			vote IS NOT NULL OR
		            			measures IS NOT NULL OR
		            			advice IS NOT NULL)';
        
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        if (! $results instanceof MDB2_Error)
        {
            $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
            return $result->advices_count;
        }
        
        return 0;
    }

    /**
     *
     * @param $id int
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
                    $this->enrollments[$id][] = $enrollment;
                }
            }
        }
        
        return $this->enrollments[$id];
    }
}
?>