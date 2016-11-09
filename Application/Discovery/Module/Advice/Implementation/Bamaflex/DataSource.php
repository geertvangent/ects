<?php
namespace Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex;

use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Doctrine\DBAL\Driver\PDOStatement;
use Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex\Advice;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;

class DataSource extends \Ehb\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $advices;

    private $enrollments;

    /**
     *
     * @param $id int
     * @return multitype:\application\discovery\module\advice\implementation\bamaflex\TeachingAssignment
     */
    public function retrieve_advices($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
            \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
            $user_id)->get_official_code();
        
        if (! isset($this->advices[$person_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($person_id));
            
            $or_conditions = array();
            $or_conditions[] = new NotCondition(
                new EqualityCondition(new StaticColumnConditionVariable('motivation'), null));
            $or_conditions[] = new NotCondition(
                new EqualityCondition(new StaticColumnConditionVariable('ombudsman'), null));
            $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('vote'), null));
            $or_conditions[] = new NotCondition(
                new EqualityCondition(new StaticColumnConditionVariable('measures'), null));
            $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('advice'), null));
            $conditions[] = new OrCondition($or_conditions);
            
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_advice_basic WHERE ' .
                 ConditionTranslator::render($condition, null, $this->get_connection()) . ' ORDER BY year DESC';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
                {
                    $advice = new Advice();
                    $advice->set_id($result->id);
                    $advice->set_enrollment_id($result->enrollment_id);
                    $advice->set_year($result->year);
                    $advice->set_person_id($result->person_id);
                    $advice->set_motivation($this->convert_to_utf8($result->motivation));
                    $advice->set_ombudsman($this->convert_to_utf8($result->ombudsman));
                    $advice->set_vote($this->convert_to_utf8($result->vote));
                    $advice->set_measures_visible($result->measures_visible);
                    $advice->set_measures($this->convert_to_utf8($result->measures));
                    $advice->set_advice_visible($result->advice_visible);
                    $advice->set_advice($this->convert_to_utf8($result->advice));
                    $advice->set_measures_valid($result->measures_valid);
                    $advice->set_date(strtotime($result->date));
                    $advice->set_try($result->try);
                    $advice->set_decision_type_id($result->decision_type_id);
                    $advice->set_decision_type($this->convert_to_utf8($result->decision_type));
                    
                    $this->advices[$person_id][] = $advice;
                }
            }
        }
        
        return $this->advices[$person_id];
    }

    public function count_advices($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
            \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
            $user_id)->get_official_code();
        
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'), 
            new StaticConditionVariable($person_id));
        
        $or_conditions = array();
        $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('motivation'), null));
        $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('ombudsman'), null));
        $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('vote'), null));
        $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('measures'), null));
        $or_conditions[] = new NotCondition(new EqualityCondition(new StaticColumnConditionVariable('advice'), null));
        $conditions[] = new OrCondition($or_conditions);
        
        $condition = new AndCondition($conditions);
        
        $query = 'SELECT count(id) AS advices_count FROM v_discovery_advice_basic WHERE ' .
             ConditionTranslator::render($condition, null, $this->get_connection());
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO::FETCH_OBJ);
            return $result->advices_count;
        }
        
        return 0;
    }

    /**
     *
     * @param $id int
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Enrollment
     */
    public function retrieve_enrollments($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->enrollments[$id]))
        {
            $user = \Chamilo\Core\User\Storage\DataManager::getInstance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT * FROM v_discovery_enrollment_advanced WHERE ' .
                 ConditionTranslator::render($condition, null, $this->get_connection()) . ' ORDER BY year DESC, id';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
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
                    $this->enrollments[$id][] = $enrollment;
                }
            }
        }
        
        return $this->enrollments[$id];
    }
}
