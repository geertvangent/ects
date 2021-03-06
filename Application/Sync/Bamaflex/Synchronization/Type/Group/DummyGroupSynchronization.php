<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

/**
 *
 * @package ehb.sync;
 */
use Chamilo\Core\Group\Storage\DataClass\Group;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

class DummyGroupSynchronization extends GroupSynchronization
{

    private $year;

    public function __construct(Group $group, $year)
    {
        $this->set_current_group($group);
        $this->year = $year;
    }

    /**
     *
     * @return string
     */
    public function get_year()
    {
        return $this->year;
    }

    /**
     *
     * @param string $year
     */
    public function set_year($year)
    {
        $this->year = $year;
    }
}
