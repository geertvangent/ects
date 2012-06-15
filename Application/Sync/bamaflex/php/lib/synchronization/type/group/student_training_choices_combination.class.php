<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

class StudentTrainingChoicesCombinationGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CO';
    
    const RESULT_PROPERTY_CHOICE_COMBINATION = 'name';
    const RESULT_PROPERTY_CHOICE_COMBINATION_ID = 'id';

    function get_combination()
    {
        return $this->get_synchronization();
    }

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . $this->get_parameter(self :: RESULT_PROPERTY_CHOICE_COMBINATION_ID);
    }

    function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_CHOICE_COMBINATION);
    }

    function get_user_official_codes()
    {
        $user_mails = array();
        
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user]  WHERE choice_option_id = "' . $this->get_parameter(self :: RESULT_PROPERTY_CHOICE_COMBINATION_ID) . '" AND type = 1 AND result != 8';
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }
        
        return $user_mails;
    }
}
?>