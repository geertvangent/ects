<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use Doctrine\DBAL\Driver\PDOStatement;
use libraries\DoctrineConditionTranslator;
use libraries\EqualityCondition;
use application\discovery\module\enrollment\DataManagerInterface;
use libraries\StaticColumnConditionVariable;
use libraries\StaticConditionVariable;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $contract_types = array();

    private $enrollments = array();

    /**
     *
     * @param unknown_type $id
     * @return multitype:int
     */
    public function retrieve_contract_types($parameters)
    {
        $user_id = $parameters->get_user_id();
        if (! isset($this->contract_types[$user_id]))
        {
            $user = \core\user\DataManager :: get_instance()->retrieve_user($user_id);
            $official_code = $user->get_official_code();

            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'),
                new StaticConditionVariable($official_code));

            $query = 'SELECT DISTINCT contract_type FROM v_discovery_enrollment_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection());

            $statement = $this->get_connection()->query($query);

            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $this->contract_types[$user_id][] = $result->contract_type;
                }
            }
        }

        return $this->contract_types[$user_id];
    }

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Enrollment
     */
    public function retrieve_enrollments($parameters)
    {
        $id = $parameters->get_user_id();
        if (! isset($this->enrollments[$id]))
        {
            $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
            $official_code = $user->get_official_code();

            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'),
                new StaticConditionVariable($official_code));

            $query = 'SELECT * FROM v_discovery_enrollment_advanced WHERE ' .
                 DoctrineConditionTranslator :: render($condition, null, $this->get_connection()) .
                 ' ORDER BY year DESC, id';

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
                    $this->enrollments[$id][] = $enrollment;
                }
            }
        }

        return $this->enrollments[$id];
    }

    public function count_enrollments($parameters)
    {
        $id = $parameters->get_user_id();
        $user = \core\user\DataManager :: get_instance()->retrieve_user($id);
        $official_code = $user->get_official_code();

        $condition = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'),
            new StaticConditionVariable($official_code));

        $query = 'SELECT count(id) AS enrollments_count FROM v_discovery_enrollment_advanced WHERE ' .
             DoctrineConditionTranslator :: render($condition, null, $this->get_connection());

        $statement = $this->get_connection()->query($query);

        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO :: FETCH_OBJ);
            return $result->enrollments_count;
        }

        return 0;
    }
}
