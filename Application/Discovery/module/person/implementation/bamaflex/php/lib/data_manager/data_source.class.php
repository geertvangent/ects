<?php
namespace application\discovery\module\person\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\person\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $persons;
    private $deans;
    private $years;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\person\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_persons()
    {
        if (! isset($this->persons))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_person_advanced] ORDER BY year DESC, name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $person = new Faculty();
                    $person->set_source($result->source);
                    $person->set_id($result->id);
                    $person->set_name($this->convert_to_utf8($result->name));
                    $person->set_year($this->convert_to_utf8($result->year));
                    $person->set_deans($this->retrieve_deans($person->get_source(), $person->get_id()));
                    
                    $this->persons[] = $person;
                }
            }
        }
        
        return $this->persons;
    }

    function retrieve_years()
    {
        if (! isset($this->years))
        {
            
            $query = 'SELECT DISTINCT [year] FROM [dbo].[v_discovery_person_advanced] ORDER BY year DESC';
            
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

    function retrieve_deans($source, $person_id)
    {
        if (! isset($this->deans[$source][$person_id]))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_person_dean_advanced] WHERE source ="' . $source . '" AND person_id = "' . $person_id . '" ORDER BY person';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $dean = new Dean();
                    $dean->set_source($result->source);
                    $dean->set_id($result->id);
                    $dean->set_person_id($result->person_id);
                    $dean->set_function_id($result->function_id);
                    $dean->set_person($this->convert_to_utf8($result->person));
                    $dean->set_function($this->convert_to_utf8($result->function));
                    
                    $this->deans[$source][$person_id][] = $dean;
                }
            }
        }
        return $this->deans[$source][$person_id];
    }
}
?>