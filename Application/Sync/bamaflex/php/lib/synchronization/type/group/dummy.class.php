<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\Group;


class DummyGroupSynchronization extends GroupSynchronization
{
    public function __construct(Group $group)
    {
        $this->set_current_group($group);
    }
}
