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

        $query = 'SELECT * FROM [dbo].[v_discovery_enrollment_basic] WHERE person_id = ' . $official_code;

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
                $enrollments[] = $enrollment;
            }
        }

        return $enrollments;
    }
}
?>