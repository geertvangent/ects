<?php
namespace Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\DataManager;

use Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\TeachingAssignment;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Doctrine\DBAL\Driver\PDOStatement;

class DataSource extends \Ehb\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $teaching_assignments;

    private $years;

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\teaching_assignment\implementation\bamaflex\TeachingAssignment
     */
    public function retrieve_teaching_assignments($parameters)
    {
        $user_id = $parameters->get_user_id();
        $year = $parameters->get_year();
        $person_id = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        
        if (! isset($this->teaching_assignments[$person_id][$year]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($person_id));
            $conditions[] = new EqualityCondition(
                new StaticColumnConditionVariable('year'), 
                new StaticConditionVariable($year));
            $condition = new AndCondition($conditions);
            
            $query = 'SELECT * FROM v_discovery_teaching_assignment WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY faculty, training, name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $teaching_assignment = new TeachingAssignment();
                    $teaching_assignment->set_source($result->source);
                    $teaching_assignment->set_programme_id($result->programme_id);
                    $teaching_assignment->set_name($this->convert_to_utf8($result->name));
                    $teaching_assignment->set_year($this->convert_to_utf8($result->year));
                    $teaching_assignment->set_training($this->convert_to_utf8($result->training));
                    $teaching_assignment->set_faculty($this->convert_to_utf8($result->faculty));
                    $teaching_assignment->set_training_id($result->training_id);
                    $teaching_assignment->set_faculty_id($result->faculty_id);
                    $teaching_assignment->set_trajectory_part($result->trajectory_part);
                    $teaching_assignment->set_credits($result->credits);
                    $teaching_assignment->set_weight($result->weight);
                    $teaching_assignment->set_timeframe_id($result->timeframe_id);
                    $teaching_assignment->set_manager($result->manager);
                    $teaching_assignment->set_teacher($result->teacher);
                    $this->teaching_assignments[$person_id][$year][] = $teaching_assignment;
                }
            }
        }
        
        return $this->teaching_assignments[$person_id][$year];
    }

    public function count_teaching_assignments($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        
        $condition = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'), 
            new StaticConditionVariable($person_id));
        
        $query = 'SELECT count(id) AS teaching_assignments_count FROM v_discovery_teaching_assignment_advanced WHERE ' .
             ConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->teaching_assignments_count;
        }
        return 0;
    }

    public function retrieve_years($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($user_id)->get_official_code();
        if (! isset($this->years[$person_id]))
        {
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($person_id));
            
            $query = 'SELECT DISTINCT year FROM v_discovery_teaching_assignment_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year DESC';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->years[$person_id][] = $result->year;
                }
            }
        }
        
        return $this->years[$person_id];
    }
}
