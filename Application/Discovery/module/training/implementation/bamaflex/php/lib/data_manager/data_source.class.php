<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\faculty\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $faculties;
    private $deans;
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
                    $faculty->set_deans($this->retrieve_deans($faculty->get_source(), $faculty->get_id()));
                    
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

    function retrieve_deans($source, $faculty_id)
    {
        if (! isset($this->deans[$source][$faculty_id]))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_faculty_dean_advanced] WHERE source ="' . $source . '" AND faculty_id = "' . $faculty_id . '" ORDER BY person';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $dean = new Dean();
                    $dean->set_source($result->source);
                    $dean->set_id($result->id);
                    $dean->set_faculty_id($result->faculty_id);
                    $dean->set_function_id($result->function_id);
                    $dean->set_person($this->convert_to_utf8($result->person));
                    $dean->set_function($this->convert_to_utf8($result->function));
                    
                    $this->deans[$source][$faculty_id][] = $dean;
                }
            }
        }
        return $this->deans[$source][$faculty_id];
    }
}
?>