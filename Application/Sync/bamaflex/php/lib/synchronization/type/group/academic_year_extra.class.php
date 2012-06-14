<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class AcademicYearExtraGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'EXT';

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Extra';
    }

    function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('academic_year_extra_generation', $this);
        $children[] = GroupSynchronization :: factory('academic_year_extra_intake', $this);
        return $children;
    }
}
?>