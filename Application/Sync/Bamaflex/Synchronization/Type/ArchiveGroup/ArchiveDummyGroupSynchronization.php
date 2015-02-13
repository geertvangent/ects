<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\ArchiveGroup;

/**
 *
 * @package ehb.sync;
 */
use Chamilo\Core\Group\Storage\DataClass\Group;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\ArchiveGroupSynchronization;

class ArchiveDummyGroupSynchronization extends ArchiveGroupSynchronization
{

    public function __construct(Group $group)
    {
        $this->set_current_group($group);
    }
}
