<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class AcademicYearGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'AY';

    function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_academic_year();
    }

    function get_name()
    {
        return $this->get_academic_year();
    }

    function get_children()
    {
        $query = 'EXEC [dbo].[sp_structure_departments] @academiejaar = N\'' . $this->get_academic_year() . '\'';
        $departments = $this->get_result($query);
        
        $children = array();
        while ($department = $departments->next_result(false))
        {
            if ($department['departement_id'] == 59)
            {
                $children[] = GroupSynchronization :: factory('department', $this, $department);
            }
        }
        
        $children[] = GroupSynchronization :: factory('academic_year_extra', $this);
        return $children;
    }
}
?>