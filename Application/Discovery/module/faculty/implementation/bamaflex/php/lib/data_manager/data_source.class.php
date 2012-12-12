<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use application\discovery\data_source\bamaflex\HistoryReference;
use application\discovery\module\faculty\DataManagerInterface;
use user\UserDataManager;
use MDB2_Error;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $faculties;

    private $faculty;

    private $deans;

    private $years;

    /**
     *
     * @param $id int
     * @return multitype:\application\discovery\module\faculty\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_faculties($year)
    {
        if (! isset($this->faculties[$year]))
        {
            $query = 'SELECT * FROM v_discovery_faculty_advanced WHERE year = "' . $year . '" ORDER BY year DESC, name';
            
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
                    
                    $reference = new HistoryReference();
                    $reference->set_id($result->previous_id);
                    $reference->set_source($result->previous_source);
                    $faculty->add_previous_reference($reference);
                    
                    $next = $this->retrieve_faculty_next_id($faculty);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($next->id);
                    $reference->set_source($next->source);
                    $faculty->add_next_reference($reference);
                    
                    $this->faculties[$year][] = $faculty;
                }
            }
        }
        
        return $this->faculties[$year];
    }

    function retrieve_faculty($faculty_parameters)
    {
        $faculty_id = $faculty_parameters->get_parameter(Faculty :: PROPERTY_ID);
        $source = $faculty_parameters->get_parameter(Faculty :: PROPERTY_SOURCE);
        
        if ($faculty_id && $source)
        {
            if (! isset($this->faculty[$faculty_id][$source]))
            {
                $query = 'SELECT * FROM v_discovery_faculty_advanced WHERE id = "' . $faculty_id . '" AND source = "' . $source . '"';
                
                $statement = $this->get_connection()->prepare($query);
                $results = $statement->execute();
                
                if (! $results instanceof MDB2_Error)
                {
                    $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
                    
                    $faculty = new Faculty();
                    $faculty->set_source($result->source);
                    $faculty->set_id($result->id);
                    $faculty->set_name($this->convert_to_utf8($result->name));
                    $faculty->set_year($this->convert_to_utf8($result->year));
                    $faculty->set_deans($this->retrieve_deans($faculty->get_source(), $faculty->get_id()));
                    
                    $reference = new HistoryReference();
                    $reference->set_id($result->previous_id);
                    $reference->set_source($result->previous_source);
                    $faculty->add_previous_reference($reference);
                    
                    $next = $this->retrieve_faculty_next_id($faculty);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($next->id);
                    $reference->set_source($next->source);
                    $faculty->add_next_reference($reference);
                    
                    $this->faculty[$faculty_id][$source] = $faculty;
                }
            }
            return $this->faculty[$faculty_id][$source];
        }
        else
        {
            return false;
        }
    }

    function retrieve_faculty_next_id($faculty)
    {
        $query = 'SELECT id, source FROM v_discovery_faculty_advanced WHERE previous_id = "' . $faculty->get_id() . '" AND source = "' . $faculty->get_source() . '"';
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        if (! $results instanceof MDB2_Error)
        {
            return $results->fetchRow(MDB2_FETCHMODE_OBJECT);
        }
        else
        {
            return false;
        }
    }

    function retrieve_years()
    {
        if (! isset($this->years))
        {
            
            $query = 'SELECT DISTINCT year FROM v_discovery_faculty_advanced ORDER BY year DESC';
            
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
            $query = 'SELECT * FROM v_discovery_faculty_dean_advanced WHERE source ="' . $source . '" AND faculty_id = "' . $faculty_id . '" ORDER BY person';
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