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
    function retrieve_careers($id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $query = 'SELECT * FROM [dbo].[v_discovery_career_basic] WHERE person_id = ' . $official_code;

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        $careers = array();

        if (! $results instanceof MDB2_Error)
        {
            while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
            {
                $career = new Career();
                $career->set_id($result->id);
                $career->set_year($this->convert_to_utf8($result->year));
                $career->set_training($this->convert_to_utf8($result->training));
                $career->set_faculty($this->convert_to_utf8($result->faculty));
                $career->set_contract_type($result->contract_type);
                $career->set_trajectory_type($result->trajectory_type);
                $career->set_trajectory($this->convert_to_utf8($result->trajectory));
                $career->set_option_choice($this->convert_to_utf8($result->option_choice));
                $career->set_graduation_option($this->convert_to_utf8($result->graduation_option));
                $careers[] = $career;
            }
        }

        return $careers;
    }
}
?>