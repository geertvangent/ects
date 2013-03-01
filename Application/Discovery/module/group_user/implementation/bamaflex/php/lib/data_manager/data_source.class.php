<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use Doctrine\DBAL\Driver\PDOStatement;
use common\libraries\DoctrineConditionTranslator;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use application\discovery\module\group\implementation\bamaflex\Group;
use application\discovery\module\group_user\DataManagerInterface;

class DataSource extends \application\discovery\data_source\bamaflex\DataSource implements DataManagerInterface
{

    private $group_user = array();

    private $group;

    /**
     *
     * @param int $programme_id
     * @return multitype:\application\discovery\module\group_user\implementation\bamaflex\GroupUser
     */
    function retrieve_group_users($group_user_parameters)
    {
        $group_class_id = $group_user_parameters->get_group_class_id();
        $source = $group_user_parameters->get_source();
        $type = $group_user_parameters->get_type();
        
        if (! isset($this->group_user[$group_class_id][$source][$type]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('group_class_id', '"' . $group_class_id . '"');
            $conditions[] = new EqualityCondition('source', '"' . $source . '"');
            $conditions[] = new EqualityCondition('type', '"' . $type . '"');
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT DISTINCT * FROM v_discovery_group_user_advanced ' . $translator->render_query($condition) . ' ORDER BY last_name, first_name';
            $statement = $this->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                while ($result = $statement->fetch(\PDO :: FETCH_OBJ))
                {
                    $group_user = new GroupUser();
                    $group_user->set_source($result->source);
                    $group_user->set_enrollment_id($result->enrollment_id);
                    $group_user->set_person_id($result->person_id);
                    $group_user->set_last_name($this->convert_to_utf8($result->last_name));
                    $group_user->set_first_name($this->convert_to_utf8($result->first_name));
                    $group_user->set_group_class_id($result->group_class_id);
                    $group_user->set_year($result->year);
                    $group_user->set_struck($result->struck);
                    $group_user->set_type($result->type);
                    $this->group_user[$group_class_id][$source][$type][] = $group_user;
                }
            }
        }
        
        return $this->group_user[$group_class_id][$source][$type];
    }

    function retrieve_group($parameters)
    {
        $group_class_id = $parameters->get_group_class_id();
        $source = $parameters->get_source();
        $type = $parameters->get_type();
        
        if (! isset($this->group[$source][$type][$group_class_id]))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition('type_id', '"' . $group_class_id . '"');
            $conditions[] = new EqualityCondition('source', '"' . $source . '"');
            $conditions[] = new EqualityCondition('type', '"' . $type . '"');
            $condition = new AndCondition($conditions);
            $translator = DoctrineConditionTranslator :: factory($this);
            
            $query = 'SELECT * FROM v_discovery_group_advanced ' . $translator->render_query($condition);
            $statement = $this->query($query);
            
            if ($statement instanceof PDOStatement)
            {
                $result = $statement->fetch(\PDO :: FETCH_OBJ);
                
                $group = new Group();
                $group->set_id($result->id);
                $group->set_source($result->source);
                
                $group->set_training_id($result->training_id);
                $group->set_year($result->year);
                $group->set_code($this->convert_to_utf8($result->code));
                $group->set_description($this->convert_to_utf8($result->description));
                $group->set_type($result->type);
                $group->set_type_id($result->type_id);
                
                $this->group[$source][$type][$group_class_id] = $group;
            }
        }
        
        return $this->group[$source][$type][$group_class_id];
    }
}
