<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\DataManager;

use Doctrine\DBAL\Driver\PDOStatement;
use Chamilo\Libraries\Storage\DoctrineConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Application\Discovery\DataSource\Bamaflex\History;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Application\Discovery\DataSource\Bamaflex\HistoryReference;
use Chamilo\Application\Discovery\Module\Training\Implementation\Bamaflex\Training;
use Chamilo\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

class DataSource extends \Chamilo\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $training_results = array();

    private $trainings = array();

    /**
     *
     * @param int $programme_id
     * @return multitype:\application\discovery\module\course_result\implementation\bamaflex\CourseResult
     */
    public function retrieve_training_results($training_results_parameters)
    {
        $training_id = $training_results_parameters->get_training_id();
        $source = $training_results_parameters->get_source();
        
        if (! isset($this->training_results[$training_id][$source]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('training_id'), 
                new StaticConditionVariable($training_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT vdea.*, vdp.first_name, vdp.last_name FROM v_discovery_enrollment_advanced AS vdea JOIN v_discovery_profile_basic AS vdp ON vdea.person_id = vdp.id WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY vdp.first_name, vdp.last_name';
            
            $statement = $this->get_connection()->query($query);
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $enrollment = new Enrollment();
                    $enrollment->set_source($result->source);
                    $enrollment->set_id($result->id);
                    $enrollment->set_year($this->convert_to_utf8($result->year));
                    $enrollment->set_training($this->convert_to_utf8($result->training));
                    $enrollment->set_training_id($result->training_id);
                    $enrollment->set_faculty($this->convert_to_utf8($result->faculty));
                    $enrollment->set_faculty_id($result->faculty_id);
                    $enrollment->set_contract_type($result->contract_type);
                    $enrollment->set_contract_id($result->contract_id);
                    $enrollment->set_trajectory_type($result->trajectory_type);
                    $enrollment->set_trajectory($this->convert_to_utf8($result->trajectory));
                    $enrollment->set_option_choice($this->convert_to_utf8($result->option_choice));
                    $enrollment->set_graduation_option($this->convert_to_utf8($result->graduation_option));
                    $enrollment->set_result($result->result);
                    $enrollment->set_distinction($result->distinction);
                    $enrollment->set_generation_student($result->generation_student);
                    $enrollment->set_person_id($result->person_id);
                    $enrollment->set_optional_property('first_name', $this->convert_to_utf8($result->first_name));
                    $enrollment->set_optional_property('last_name', $this->convert_to_utf8($result->last_name));
                    $this->training_results[$training_id][$source][] = $enrollment;
                }
            }
        }
        
        return $this->training_results[$training_id][$source];
    }

    public function retrieve_training($training_parameters)
    {
        $training_id = $training_parameters->get_training_id();
        $source = $training_parameters->get_source();
        
        if (! isset($this->trainings[$training_id][$source]))
        {
            
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('id'), 
                new StaticConditionVariable($training_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('source'), 
                new StaticConditionVariable($source));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_training_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
            
            $statement = $this->get_connection()->query($query);
            
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
                    $training->set_faculty($this->convert_to_utf8($result->faculty));
                    $training->set_start_date($result->start_date);
                    $training->set_end_date($result->end_date);
                    
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(History :: class_name(), History :: PROPERTY_HISTORY_ID), 
                        new StaticConditionVariable($training->get_id()));
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(History :: class_name(), History :: PROPERTY_HISTORY_SOURCE), 
                        new StaticConditionVariable($training->get_source()));
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(History :: class_name(), History :: PROPERTY_TYPE), 
                        new StaticConditionVariable(Utilities :: get_namespace_from_object($training)));
                    $condition = new AndCondition($conditions);
                    
                    $histories = \Chamilo\Application\Discovery\DataSource\Bamaflex\DataManager :: get_instance()->retrieve_history_by_conditions(
                        $condition);
                    
                    if ($histories->size() > 0)
                    {
                        while ($history = $histories->next_result())
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($history->get_previous_id());
                            $reference->set_source($history->get_previous_source());
                            $training->add_previous_reference($reference);
                        }
                    }
                    else
                    {
                        if ($result->previous_id)
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($result->previous_id);
                            $reference->set_source($result->previous_source);
                            $training->add_previous_reference($reference);
                        }
                    }
                    
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(History :: class_name(), History :: PROPERTY_PREVIOUS_ID), 
                        new StaticConditionVariable($training->get_id()));
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(History :: class_name(), History :: PROPERTY_PREVIOUS_SOURCE), 
                        new StaticConditionVariable($training->get_source()));
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(History :: class_name(), History :: PROPERTY_TYPE), 
                        new StaticConditionVariable(Utilities :: get_namespace_from_object($training)));
                    $condition = new AndCondition($conditions);
                    
                    $histories = \Chamilo\Application\Discovery\DataSource\Bamaflex\DataManager :: get_instance()->retrieve_history_by_conditions(
                        $condition);
                    if ($histories->size() > 0)
                    {
                        while ($history = $histories->next_result())
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($history->get_history_id());
                            $reference->set_source($history->get_history_source());
                            $training->add_next_reference($reference);
                        }
                    }
                    else
                    {
                        $next = $this->retrieve_training_next_id($training);
                        
                        if ($next)
                        {
                            $reference = new HistoryReference();
                            $reference->set_id($next->id);
                            $reference->set_source($next->source);
                            $training->add_next_reference($reference);
                        }
                    }
                    
                    $this->trainings[$training_id][$source] = $training;
                }
            }
        }
        
        return $this->trainings[$training_id][$source];
    }

    public function retrieve_training_next_id($training)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('previous_id'), 
            new StaticConditionVariable($training->get_id()));
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('source'), 
            new StaticConditionVariable($training->get_source()));
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT id, source FROM v_discovery_training_advanced WHERE ' .
             DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            return $result = $statement->fetch(\PDO :: FETCH_OBJ);
        }
        else
        {
            return false;
        }
    }
}
