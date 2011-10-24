<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\faculty\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $faculties;
    private $years;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\faculty\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_faculties()
    {
        if (! isset($this->faculties))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_faculty_advanced] ORDER BY year DESC, name';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $faculty = new Faculty();
                    $faculty->set_source($result->source);
                    $faculty->set_id($result->id);
                    $faculty->set_name($this->convert_to_utf8($result->name));
                    $faculty->set_year($this->convert_to_utf8($result->year));

                    $this->faculties[] = $faculty;
                }
            }
        }

        return $this->faculties;
    }

    function retrieve_years()
    {
        if (! isset($this->years))
        {

            $query = 'SELECT DISTINCT [year] FROM [dbo].[v_discovery_faculty_advanced] ORDER BY year DESC';

            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();

            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $this->years[] = $result->year;
                }
            }
        }

        return $this->years;
    }
}
?>