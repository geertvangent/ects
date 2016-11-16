<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingChoicesOptionGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'OP';
    const RESULT_PROPERTY_CHOICE_OPTION = 'name';
    const RESULT_PROPERTY_CHOICE_OPTION_ID = 'id';

    public function get_option()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . $this->get_parameter(
            self::RESULT_PROPERTY_CHOICE_OPTION_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_CHOICE_OPTION);
    }

    public function get_user_official_codes()
    {
        $user_mails = array();
        
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE choice_id = ' .
             $this->get_parameter(self::RESULT_PROPERTY_CHOICE_OPTION_ID) . ' AND type = 1 AND result != 8';
        
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }
        
        return $user_mails;
    }
}
