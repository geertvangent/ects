<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */
class AcademicYearExtraGenerationGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'GEN';

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Generatiestudenten';
    }

    public function get_user_official_codes()
    {
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE year = "' .
             $this->get_academic_year() . '" AND generation_student = 1 AND type = 1';
        $users = $this->get_result($query);
        
        $user_mails = array();
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }
        return $user_mails;
    }
}
