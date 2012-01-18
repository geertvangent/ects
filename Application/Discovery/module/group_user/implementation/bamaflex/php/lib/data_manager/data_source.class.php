<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use application\discovery\module\group\implementation\bamaflex\Group;

use common\libraries\ArrayResultSet;
use user\UserDataManager;

use application\discovery\module\group_user\MarkMoment;
use application\discovery\module\group_user\DataManagerInterface;

use MDB2_Error;

class DataSource extends \application\discovery\connection\bamaflex\DataSource implements DataManagerInterface
{
    private $group_user = array();
    private $group;

    /**
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
            $query = 'SELECT DISTINCT * FROM [dbo].[v_discovery_group_user_advanced] ';
            $query .= 'WHERE group_class_id = "' . $group_class_id . '" AND source = "' . $source . '" AND type = "' . $type . '" ';
            $query .= 'ORDER BY last_name, first_name';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                while ($result = $results->fetchRow(MDB2_FETCHMODE_OBJECT))
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
            $query = 'SELECT * FROM [dbo].[v_discovery_group_advanced] WHERE type_id = "' . $group_class_id . '" AND source = "' . $source . '" AND type = "' . $type . '"';
            
            $statement = $this->get_connection()->prepare($query);
            $results = $statement->execute();
            
            if (! $results instanceof MDB2_Error)
            {
                $result = $results->fetchRow(MDB2_FETCHMODE_OBJECT);
                
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
?>