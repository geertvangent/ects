<?php
namespace application\discovery\module\employment\implementation\bamaflex;

use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\DoctrineConditionTranslator;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use application\discovery\module\employment\DataManagerInterface;
use user\UserDataManager;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $employments = array();

    private $employment_parts = array();

    /**
     *
     * @param int $id
     * @return \application\discovery\module\employment\implementation\bamaflex\Employment boolean
     */
    public function retrieve_employments($parameters)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($parameters->get_user_id());
        
        $official_code = $user->get_official_code();
        
        $conditions = array();
        $conditions[] = new EqualityCondition('person_id', '"' . $official_code . '"');
        $conditions[] = new EqualityCondition('active', 1);
        $condition = new AndCondition($conditions);
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT * FROM v_discovery_employment ' . $translator->render_query($condition) . ' ORDER BY start_date DESC';
        
        $statement = $this->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
            {
                $employment = new Employment();
                $employment->set_id($result->id);
                $employment->set_person_id($result->person_id);
                $employment->set_year($result->year);
                $employment->set_assignment($result->assignment);
                $employment->set_hours($result->hours);
                $employment->set_start_date(strtotime($result->start_date));
                $employment->set_end_date(strtotime($result->end_date));
                $employment->set_state_id($result->state_id);
                $employment->set_state($result->state);
                $employment->set_state_code($result->state_code);
                $employment->set_office_id($result->office_id);
                $employment->set_office($result->office);
                $employment->set_category_id($result->category_id);
                $employment->set_category_code($result->category_code);
                $employment->set_category($result->category);
                $employment->set_category_description($result->category_description);
                $employment->set_description($result->description);
                $employment->set_fund_id($result->fund_id);
                $employment->set_fund($result->fund);
                $employment->set_pay_scale_id($result->pay_scale_id);
                $employment->set_pay_scale($result->pay_scale);
                $employment->set_pay_scale_minimum_age($result->pay_scale_minimum_age);
                $employment->set_pay_scale_minimum_wage($result->pay_scale_minimum_wage);
                $employment->set_pay_scale_maximum_wage($result->scale_maximum_wage);
                $employment->set_active($result->active);
                $employment->set_cycles($result->cycles);
                $employment->set_interruption($result->interruption);
                $employment->set_interruption_id($result->interruption_id);
                $employment->set_interruption_category($result->interruption_category);
                $employment->set_interruption_category_id($result->interruption_category_id);
                
                $this->employments[$official_code][] = $employment;
            }
            
            return $this->employments[$official_code];
        }
        else
        {
            return false;
        }
    }

    public function count_employments($parameters)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($parameters->get_user_id());
        
        $official_code = $user->get_official_code();
        
        $condition = new EqualityCondition('person_id', '"' . $official_code . '"');
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT count(id) AS employments_count FROM v_discovery_employment ' . $translator->render_query(
                $condition);
        
        $statement = $this->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $result = $statement->fetch(\PDO :: FETCH_OBJ);
            
            return $result->employments_count;
        }
        else
        {
            return 0;
        }
    }

    public function retrieve_employment_parts($employment_id)
    {
        $condition = new EqualityCondition('assignment_id', '"' . $employment_id . '"');
        $translator = DoctrineConditionTranslator :: factory($this);
        
        $query = 'SELECT * FROM v_discovery_employment_parts ' . $translator->render_query($condition) . ' ORDER BY start_date';
        
        $statement = $this->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
            {
                $employment_part = new EmploymentPart();
                $employment_part->set_assignment_id($result->assignment_id);
                $employment_part->set_hours($result->hours);
                $employment_part->set_start_date(strtotime($result->start_date));
                $employment_part->set_end_date(strtotime($result->end_date));
                $employment_part->set_assignment_volume($result->assignment_volume);
                $employment_part->set_volume($result->volume);
                $employment_part->set_faculty_id($result->faculty_id);
                $employment_part->set_faculty($result->faculty);
                $employment_part->set_training_id($result->training_id);
                $employment_part->set_training($result->training);
                $employment_part->set_department($result->department);
                $employment_part->set_department_id($result->department_id);
                
                $this->employment_parts[$employment_id][] = $employment_part;
            }
            
            return $this->employment_parts[$employment_id];
        }
        else
        {
            return false;
        }
    }
}
