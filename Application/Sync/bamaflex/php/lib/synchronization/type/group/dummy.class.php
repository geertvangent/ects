<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class DummyGroupSynchronization extends GroupSynchronization
{
    function __construct(Group $group)
    {
        $this->set_current_group($group);
    }
}
?>