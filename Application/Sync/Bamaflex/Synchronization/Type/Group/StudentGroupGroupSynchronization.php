<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class StudentGroupGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'GRO_';
    const RESULT_PROPERTY_CODE = 'code';
    const RESULT_PROPERTY_DESCRIPTION = 'description';
    const RESULT_PROPERTY_TYPE_ID = 'type_id';
    const RESULT_PROPERTY_TYPE = 'type';

    public function get_code()
    {
        return $this::IDENTIFIER . $this->get_parameter(self::RESULT_PROPERTY_TYPE) . '_' .
             $this->get_parameter(self::RESULT_PROPERTY_TYPE_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_DESCRIPTION) . ' (' .
             $this->get_parameter(self::RESULT_PROPERTY_CODE) . ')';
    }

    public function get_user_official_codes()
    {
        $user_ids = array();
        
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_group_user_advanced]  WHERE source = 1 AND group_class_id = ' .
             $this->get_parameter(self::RESULT_PROPERTY_TYPE_ID) . ' AND type = ' .
             $this->get_parameter(self::RESULT_PROPERTY_TYPE);
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $user_ids[] = $user['person_id'];
        }
        
        return $user_ids;
    }
}
