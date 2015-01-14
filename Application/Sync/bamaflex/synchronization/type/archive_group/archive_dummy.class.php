<?php
namespace Application\EhbSync\bamaflex\synchronization\type\archive_group;

/**
 *
 * @package ehb.sync;
 */
use core\group\Group;

class ArchiveDummyGroupSynchronization extends ArchiveGroupSynchronization
{

    public function __construct(Group $group)
    {
        $this->set_current_group($group);
    }
}
