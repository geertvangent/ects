<?php
namespace application\discovery\module\training\implementation\bamaflex;

use user\UserDataManager;

use application\discovery\module\training\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $trainings;
    private $years;

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\training\implementation\bamaflex\TeachingAssignment
     */
    function retrieve_trainings()
    {
        if (! isset($this->trainings))
        {
            $query = 'SELECT * FROM [dbo].[v_discovery_training_advanced] ORDER BY year DESC, name';
            
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
                    $training->set_goals($this->convert_to_utf8($result->goals));
                    $training->set_type_id($result->type_id);
                    $training->set_type($this->convert_to_utf8($result->type));
                    $training->set_bama_type($result->bama_type);
                    
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
}
?>