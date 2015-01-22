<?php
namespace Chamilo\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\DataManager;

use Chamilo\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\StudentYear;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Doctrine\DBAL\Driver\PDOStatement;

class DataSource extends \Chamilo\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $student_years = array();

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\student_year\implementation\bamaflex\StudentYear
     */
    public function retrieve_student_years($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->student_years[$id]))
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT * FROM v_discovery_year_advanced WHERE ' .
                 ConditionTranslator :: render($condition, null, $this->get_connection()) . ' ORDER BY year DESC, id';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $student_year = new StudentYear();
                    $student_year->set_source($result->source);
                    $student_year->set_id($result->id);
                    $student_year->set_person_id($id);
                    $student_year->set_year($this->convert_to_utf8($result->year));
                    $student_year->set_scholarship_id($result->scholarship_id);
                    $student_year->set_reduced_registration_fee_id($result->reduced_registration_fee_id);
                    $student_year->set_enrollment_id($result->enrollment_id);
                    
                    $this->student_years[$id][] = $student_year;
                }
            }
        }
        
        return $this->student_years[$id];
    }

    public function count_student_years($parameters)
    {
        $id = $parameters->get_user_id();
        $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        
        $condition = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'), 
            new StaticConditionVariable($official_code));
        
        $query = 'SELECT count(id) AS student_years_count FROM v_discovery_year_advanced WHERE ' .
             ConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->student_years_count;
        }
        
        return 0;
    }
}
