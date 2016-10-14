<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class Faculty extends GroupSynchronization
{
    CONST IDENTIFIER = 'CUR_FAC';
    const RESULT_PROPERTY_CODE = 'code';
    const RESULT_PROPERTY_DEPARTMENT = 'name';

    public function get_code()
    {
        return self::IDENTIFIER . '_' . $this->get_parameter(self::RESULT_PROPERTY_CODE);
    }

    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_DEPARTMENT);
    }

    public function get_children()
    {
        $children = array();

        $children[] = GroupSynchronization::factory(
            '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Employee',
            $this);
        $children[] = GroupSynchronization::factory(
            '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Student',
            $this);

        return $children;
    }
}
