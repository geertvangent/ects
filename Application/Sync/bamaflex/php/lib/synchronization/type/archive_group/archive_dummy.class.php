<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */
use group\Group;

class ArchiveDummyGroupSynchronization extends ArchiveGroupSynchronization
{

    public function __construct(Group $group)
    {
        $this->set_current_group($group);
    }
}
