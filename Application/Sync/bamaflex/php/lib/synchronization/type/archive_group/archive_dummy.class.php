<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\Group;


class ArchiveDummyGroupSynchronization extends ArchiveGroupSynchronization
{
    function __construct(Group $group)
    {
        $this->set_current_group($group);
    }
}
