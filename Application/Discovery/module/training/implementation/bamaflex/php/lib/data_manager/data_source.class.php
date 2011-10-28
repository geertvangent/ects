<?php
namespace application\discovery\module\training\implementation\bamaflex;

use application\discovery\module\faculty\implementation\bamaflex\Dean;

use application\discovery\module\faculty\implementation\bamaflex\Faculty;

use user\UserDataManager;

use application\discovery\module\training\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $trainings;
    private $years;
    
    private $faculties;
    private $deans;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\training\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_trainings($training_parameters)
    {
        $faculty_id = $training_parameters->get_faculty_id();
        $source = $training_parameters->get_source();
        
        if (! isset($this->trainings))
        {
            
            $query = 'SELECT * FROM [dbo].[v_discovery_training_advanced]';
            if ($faculty_id && $source)
            {
                $query .= ' WHERE faculty_id = "' . $faculty_id . '" AND source = "' . $source . '"';
            }
            $query .= ' ORDER BY year DESC, name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $training = new Training();
                    $training->set_source($result->source);
                    $training->set_id($result->id);
                    $training->set_name($this->convert_to_utf8($result->name));
                    $training->set_year($this->convert_to_utf8($result->year));
                    $training->set_credits($result->credits);
                    $training->set_domain_id($result->domain_id);
                    $training->set_domain($this->convert_to_utf8($result->domain));
                    $training->set_goals(nl2br($this->convert_to_utf8($result->goals)));
                    $training->set_type_id($result->type_id);
                    $training->set_type($this->convert_to_utf8($result->type));
                    $training->set_bama_type($result->bama_type);
                    $training->set_faculty_id($result->faculty_id);
                    $training->set_start_date($result->start_date);
                    $training->set_end_date($result->end_date);
                    
                    $this->trainings[] = $training;
                }
            }
        }
        
        return $this->trainings;
    }

    function retrieve_years()
    {
        if (! isset($this->years))
        {
            $query = 'SELECT DISTINCT [year] FROM [dbo].[v_discovery_training_advanced] ORDER BY year DESC';
            
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

    function retrieve_faculty($training_parameters)
    {
        $faculty_id = $training_parameters->get_faculty_id();
        $source = $training_parameters->get_source();
        if ($faculty_id && $source)
        {
            if (! isset($this->faculties[$faculty_id][$source]))
            {
                $query = 'SELECT * FROM [dbo].[v_discovery_faculty_advanced] WHERE id = "' . $faculty_id . '" AND source = "' . $source . '"';
                
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
                    
                    $this->faculties[$faculty_id][$source] = $faculty;
                }
            }
            return $this->faculties[$faculty_id][$source];
        }
        else
        {
            return false;
        }
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