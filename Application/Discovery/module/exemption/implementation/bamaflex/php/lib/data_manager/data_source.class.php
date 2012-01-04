<?php
namespace application\discovery\module\exemption\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\exemption\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $exemptions;
    private $years;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\exemption\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_exemptions($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = UserDataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        
        if (! isset($this->exemptions[$person_id]))
        {
            //                     $official_code = $user->get_official_code();
            

            $query = 'SELECT * FROM [dbo].[v_discovery_exemption_basic] WHERE person_id = ' . $person_id . ' ORDER BY year DESC, programme_name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $exemption = new Exemption();
                    $exemption->set_id($result->id);
                    $exemption->set_enrollment_id($result->enrollment_id);
                    $exemption->set_year($result->year);
                    $exemption->set_programme_id($result->programme_id);
                    $exemption->set_programme_name($this->convert_to_utf8($result->programme_name));
                    $exemption->set_type_id($result->type_id);
                    $exemption->set_type($this->convert_to_utf8($result->type));
                    $exemption->set_result($result->result);
                    $exemption->set_state($result->state);
                    $exemption->set_credits($result->credits);
                    $exemption->set_proof($this->convert_to_utf8($result->proof));
                    $exemption->set_date_requested(strtotime($result->date_requested));
                    $exemption->set_date_closed(strtotime($result->date_closed));
                    $exemption->set_remarks_public($this->convert_to_utf8($result->remarks_public));
                    $exemption->set_remarks_private($this->convert_to_utf8($result->remarks_private));
                    $exemption->set_motivation($this->convert_to_utf8($result->motivation));
                    $exemption->set_external_id($result->external_id);
                    $exemption->set_external($this->convert_to_utf8($result->external));
                    $this->exemptions[$person_id][] = $exemption;
                }
            }
        }
        
        return $this->exemptions[$person_id];
    }

    function retrieve_years($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = UserDataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        if (! isset($this->years[$person_id]))
        {
            $query = 'SELECT DISTINCT [year] FROM [dbo].[v_discovery_exemption_basic] WHERE person_id = ' . $person_id . ' ORDER BY year DESC';
            
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