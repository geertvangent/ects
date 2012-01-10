<?php
namespace application\discovery\module\student_year\implementation\bamaflex;

use application\discovery\module\student_year\DataManagerInterface;

use user\UserDataManager;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $student_years = array();

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\student_year\implementation\bamaflex\StudentYear
     */
    function retrieve_student_years($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->student_years[$id]))
        {
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();

            $query = 'SELECT * FROM [dbo].[v_discovery_year_advanced] WHERE person_id = ' . $official_code . ' ORDER BY year DESC, id';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $student_year = new StudentYear();
                    $student_year->set_source($result->source);
                    $student_year->set_id($result->id);
                    $student_year->set_person_id($id);
                    $student_year->set_year($this->convert_to_utf8($result->year));
                    $student_year->set_scholarship_id($result->scholarship_id);
                    $student_year->set_reduced_registration_fee_id($result->reduced_registration_fee_id);
                    $student_year->set_enrollment_id($result->enrollment_id);

                    $this->student_years[$id][] = $student_year;
                }
            }
        }

        return $this->student_years[$id];
    }

    function count_student_years($parameters)
    {
        $id = $parameters->get_user_id();
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $query = 'SELECT count(id) AS student_years_count FROM [dbo].[v_discovery_year_advanced] WHERE person_id = "' . $official_code . '"';

        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();

        if (! $results instanceof MDB2_Error)
        {
            $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
            return $result->student_years_count;
        }

        return 0;
    }
}
?>