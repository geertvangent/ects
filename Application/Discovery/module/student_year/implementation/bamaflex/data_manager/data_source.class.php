<?php
namespace Application\Discovery\module\student_year\implementation\bamaflex\data_manager;

use libraries\storage\DoctrineConditionTranslator;
use Doctrine\DBAL\Driver\PDOStatement;
use libraries\storage\EqualityCondition;
use libraries\storage\StaticColumnConditionVariable;
use libraries\storage\StaticConditionVariable;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource
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
            $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($official_code));
            
            $query = 'SELECT * FROM v_discovery_year_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY year DESC, id';
            
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
        $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        
        $condition = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'), 
            new StaticConditionVariable($official_code));
        
        $query = 'SELECT count(id) AS student_years_count FROM v_discovery_year_advanced WHERE ' .
             DoctrineConditionTranslator :: render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->student_years_count;
        }
        
        return 0;
    }
}
