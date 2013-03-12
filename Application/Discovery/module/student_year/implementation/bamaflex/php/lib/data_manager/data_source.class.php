<?php
namespace application\discovery\module\student_year\implementation\bamaflex;

use common\libraries\DoctrineConditionTranslator;
use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\EqualityCondition;
use application\discovery\module\student_year\DataManagerInterface;
use user\UserDataManager;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
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
            $user = UserDataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();
            
            $condition = new EqualityCondition('person_id', '"' . $official_code . '"');
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_year_advanced ' . $translator->render_query($condition) . ' ORDER BY year DESC, id';
            
            $statement = $this->query($query);
            
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
        $user = UserDataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();
        
        $condition = new EqualityCondition('person_id', '"' . $official_code . '"');
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT count(id) AS student_years_count FROM v_discovery_year_advanced ' . $translator->render_query(
                $condition);
        
        $statement = $this->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->student_years_count;
        }
        
        return 0;
    }
}
