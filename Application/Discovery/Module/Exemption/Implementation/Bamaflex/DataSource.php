<?php
namespace Ehb\Application\Discovery\Module\Exemption\Implementation\Bamaflex;

use Chamilo\Libraries\Storage\DataManager\Doctrine\Condition\ConditionTranslator;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticColumnConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Doctrine\DBAL\Driver\PDOStatement;
use Ehb\Application\Discovery\Module\Exemption\Implementation\Bamaflex\Exemption;

class DataSource extends \Ehb\Application\Discovery\DataSource\Bamaflex\DataSource
{

    private $exemptions;

    private $years;

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\exemption\implementation\bamaflex\TeachingAssignment
     */
    public function retrieve_exemptions($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
            \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
            $user_id)->get_official_code();
        
        if (! isset($this->exemptions[$person_id]))
        {
            $condition = new EqualityCondition(
                new StaticColumnConditionVariable('person_id'), 
                new StaticConditionVariable($person_id));
            
            $query = 'SELECT * FROM v_discovery_exemption_basic WHERE ' .
                 ConditionTranslator::render($condition, null, $this->get_connection()) .
                 ' ORDER BY year DESC, programme_name';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
                {
                    $exemption = new Exemption();
                    $exemption->set_id($result->id);
                    $exemption->set_enrollment_id($result->enrollment_id);
                    $exemption->set_year($result->year);
                    $exemption->set_programme_id($result->programme_id);
                    $exemption->set_programme_name($this->convert_to_utf8($result->programme_name));
                    $exemption->set_type_id($result->type_id);
                    $exemption->set_type($this->convert_to_utf8($result->type));
                    $exemption->set_result($result->result);
                    $exemption->set_state($result->state);
                    $exemption->set_credits($result->credits);
                    $exemption->set_proof($this->convert_to_utf8($result->proof));
                    $exemption->set_date_requested(strtotime($result->date_requested));
                    $exemption->set_date_closed(strtotime($result->date_closed));
                    $exemption->set_remarks_public($this->convert_to_utf8($result->remarks_public));
                    $exemption->set_remarks_private($this->convert_to_utf8($result->remarks_private));
                    $exemption->set_motivation($this->convert_to_utf8($result->motivation));
                    $exemption->set_external_id($result->external_id);
                    $exemption->set_external($this->convert_to_utf8($result->external));
                    $this->exemptions[$person_id][] = $exemption;
                }
            }
        }
        
        return $this->exemptions[$person_id];
    }

    public function count_exemptions($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
            \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
            $user_id)->get_official_code();
        
        $condition = new EqualityCondition(
            new StaticColumnConditionVariable('person_id'), 
            new StaticConditionVariable($person_id));
        
        $query = 'SELECT count(id) AS exemptions_count FROM v_discovery_exemption_basic WHERE ' .
             ConditionTranslator::render($condition, null, $this->get_connection());
        
        $statement = $this->get_connection()->query($query);
        
        if ($statement instanceof PDOStatement)
        {
            $result = $statement->fetch(\PDO::FETCH_OBJ);
            return $result->exemptions_count;
        }
        
        return 0;
    }

    public function retrieve_years($parameters)
    {
        $user_id = $parameters->get_user_id();
        $person_id = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
            \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
            $user_id)->get_official_code();
        if (! isset($this->years[$person_id]))
        {
            $query = 'SELECT DISTINCT year FROM v_discovery_exemption_basic WHERE person_id = "' . $person_id .
                 '" ORDER BY year DESC';
            
            $statement = $this->get_connection()->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO::FETCH_OBJ))
                {
                    $this->years[$person_id][] = $result->year;
                }
            }
        }
        
        return $this->years[$person_id];
    }
}
