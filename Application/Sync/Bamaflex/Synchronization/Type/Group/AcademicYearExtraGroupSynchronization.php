<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
class AcademicYearExtraGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'EXT';

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Extra';
    }

    public function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('academic_year_extra_generation', $this);
        $children[] = GroupSynchronization :: factory('academic_year_extra_intake', $this);
        return $children;
    }
}
