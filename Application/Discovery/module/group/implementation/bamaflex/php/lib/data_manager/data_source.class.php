<?php
namespace application\discovery\module\group\implementation\bamaflex;

use application\discovery\module\group\DataManagerInterface;

use application\discovery\module\training\implementation\bamaflex\Training;
use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $groups;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\group\implementation\bamaflex\Group
     */
    function retrieve_groups($parameters)
    {
        $training_id = $parameters->get_training_id();
        $source = $parameters->get_source();
        
        if (! isset($this->groups[$training_id]))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_group_advanced] WHERE training_id = "' . $training_id . '" AND source = "' . $source . '" ORDER BY description';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
                {
                    $group = new Group();
                    $group->set_id($result->id);
                    $group->set_source($result->source);
                    $group->set_training_id($result->training_id);
                    $group->set_year($result->year);
                    $group->set_code($this->convert_to_utf8($result->code));
                    $group->set_description($this->convert_to_utf8($result->description));
                    $group->set_type($result->type);
                    $group->set_type_id($result->type_id);
                    
                    $this->groups[$training_id][] = $group;
                }
            }
        }
        
        return $this->groups[$training_id];
    }

    function retrieve_training($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();
        
        if (! isset($this->trainings[$training_id][$source]))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_training_advanced] WHERE id = "' . $training_id . '" AND source = "' . $source . '"';
            
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
                    $training->set_faculty($result->faculty);
                    $training->set_start_date($result->start_date);
                    $training->set_end_date($result->end_date);
                    $training->set_previous_id($result->previous_id);
                    $training->set_next_id($this->retrieve_training_next_id($training));
                    
                    
                    $this->trainings[$training_id][$source] = $training;
                }
            }
        }
        
        return $this->trainings[$training_id][$source];
    }

    function retrieve_training_next_id($training)
    {
        $query = 'SELECT id FROM [dbo].[v_discovery_training_advanced] WHERE previous_id = "' . $training->get_id() . '" AND source = "' . $training->get_source() . '"';
        $statement = $this->get_connection()->prepare($query);
        $results = $statement->execute();
        
        if (! $results instanceof MDB2_Error)
        {
            $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
            return $result->id;
        }
        else
        {
            return false;
        }
    }

}
?>