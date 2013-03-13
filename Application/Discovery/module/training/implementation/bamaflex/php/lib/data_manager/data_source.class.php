<?php
namespace application\discovery\module\training\implementation\bamaflex;

use common\libraries\NotCondition;
use common\libraries\DoctrineConditionTranslator;
use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\Utilities;
use application\discovery\data_source\bamaflex\History;
use application\discovery\data_source\bamaflex\DataManager;
use application\discovery\module\faculty\implementation\bamaflex\Dean;
use application\discovery\module\faculty\implementation\bamaflex\Faculty;
use application\discovery\module\training\DataManagerInterface;
use application\discovery\data_source\bamaflex\HistoryReference;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $trainings;

    private $years;

    private $faculties;

    private $deans;

    /**
     *
     * @param $id int
     * @return multitype:\application\discovery\module\training\implementation\bamaflex\TeachingAssignment
     */
    public function retrieve_trainings($year)
    {
        if (! isset($this->trainings[$year]))
        {
            $condition = new EqualityCondition('year', '"' . $year . '"');
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_training_advanced ' . $translator->render_query($condition) .
                 ' ORDER BY year DESC, name';
            $statement = $this->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
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
                    
                    $reference = new HistoryReference();
                    $reference->set_id($result->previous_id);
                    $reference->set_source($result->previous_source);
                    $training->add_previous_reference($reference);
                    
                    $next = $this->retrieve_training_next_id($training);
                    
                    $reference = new HistoryReference();
                    $reference->set_id($next->id);
                    $reference->set_source($next->source);
                    $training->add_next_reference($reference);
                    
                    $this->trainings[$year][] = $training;
                }
            }
        }
        
        return $this->trainings[$year];
    }

    public function retrieve_training_next_id($training)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition('previous_id', '"' . $training->get_id() . '"');
        $conditions[] = new EqualityCondition('source', '"' . $training->get_source() . '"');
        $condition = new AndCondition($conditions);
        
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT id, source FROM v_discovery_training_advanced ' . $translator->render_query($condition);
        $statement = $this->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            return $statement->fetch(\PDO :: FETCH_OBJ);
        }
        else
        {
            return false;
        }
    }

    public function retrieve_years()
    {
        if (! isset($this->years))
        {
            $condition = new NotCondition(new EqualityCondition('year', null));
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT DISTINCT year FROM v_discovery_training_advanced ' . $translator->render_query($condition) .
                 ' ORDER BY year DESC';
            
            $statement = $this->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->years[] = $result->year;
                }
            }
        }
        
        return $this->years;
    }

    public function retrieve_faculty($faculty_parameters)
    {
        $faculty_id = $faculty_parameters->get_faculty_id();
        $source = $faculty_parameters->get_source();
        if ($faculty_id && $source)
        {
            if (! isset($this->faculties[$faculty_id][$source]))
            {
                $conditions = array();
                $conditions[] = new EqualityCondition('id', '"' . $faculty_id . '"');
                $conditions[] = new EqualityCondition('source', '"' . $source . '"');
                $condition = new AndCondition($conditions);
                $translator = DoctrineConditionTranslator :: factory($this);
                
                $query = 'SELECT * FROM v_discovery_faculty_advanced ' . $translator->render_query($condition);
                
                $statement = $this->query($query);
                
                if ($statement instanceof PDOStatement)
                {
                    $result = $statement->fetch(\PDO :: FETCH_OBJ);
                    
                    $faculty = new Faculty();
                    $faculty->set_source($result->source);
                    $faculty->set_id($result->id);
                    $faculty->set_name($this->convert_to_utf8($result->name));
                    $faculty->set_year($this->convert_to_utf8($result->year));
                    $faculty->set_deans($this->retrieve_deans($faculty->get_source(), $faculty->get_id()));
                    
                    $conditions = array();
                    $conditions[] = new EqualityCondition(History :: PROPERTY_HISTORY_ID, $faculty->get_id());
                    $conditions[] = new EqualityCondition(History :: PROPERTY_HISTORY_SOURCE, $faculty->get_source());
                    $conditions[] = new EqualityCondition(
                        History :: PROPERTY_TYPE, 
                        Utilities :: get_namespace_from_object($faculty));
                    $condition = new AndCondition($conditions);
                    
                    $histories = DataManager :: get_instance()->retrieve_history_by_conditions($condition);
                    if ($histories->size() > 0)
                    {
                        while ($history = $histories->next_result())
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($history->get_previous_id());
                            $reference->set_source($history->get_previous_source());
                            $faculty->add_previous_reference($reference);
                        }
                    }
                    else
                    {
                        if ($result->previous_id)
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($result->previous_id);
                            $reference->set_source($result->previous_source);
                            $faculty->add_previous_reference($reference);
                        }
                    }
                    
                    $conditions = array();
                    $conditions[] = new EqualityCondition(History :: PROPERTY_PREVIOUS_ID, $faculty->get_id());
                    $conditions[] = new EqualityCondition(History :: PROPERTY_PREVIOUS_SOURCE, $faculty->get_source());
                    $conditions[] = new EqualityCondition(
                        History :: PROPERTY_TYPE, 
                        Utilities :: get_namespace_from_object($faculty));
                    $condition = new AndCondition($conditions);
                    
                    $histories = DataManager :: get_instance()->retrieve_history_by_conditions($condition);
                    if ($histories->size() > 0)
                    {
                        while ($history = $histories->next_result())
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($history->get_history_id());
                            $reference->set_source($history->get_history_source());
                            $faculty->add_next_reference($reference);
                        }
                    }
                    else
                    {
                        $next = $this->retrieve_faculty_next_id($faculty);
                        
                        if ($next)
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($next->id);
                            $reference->set_source($next->source);
                            $faculty->add_next_reference($reference);
                        }
                    }
                    
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

    public function retrieve_faculty_next_id($faculty)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition('previous_id', '"' . $faculty->get_id() . '"');
        $conditions[] = new EqualityCondition('source', '"' . $faculty->get_source() . '"');
        $condition = new AndCondition($conditions);
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT id, source FROM v_discovery_faculty_advanced ' . $translator->render_query($condition);
        $statement = $this->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
        }
        else
        {
            return false;
        }
    }

    public function retrieve_deans($source, $faculty_id)
    {
        if (! isset($this->deans[$source][$faculty_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('source', '"' . $source . '"');
            $conditions[] = new EqualityCondition('faculty_id', '"' . $faculty_id . '"');
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_faculty_dean_advanced ' . $translator->render_query($condition) .
                 ' ORDER BY person';
            
            $statement = $this->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
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
