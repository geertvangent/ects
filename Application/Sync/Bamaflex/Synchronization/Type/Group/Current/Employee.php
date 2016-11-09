<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Employee extends GroupSynchronization
{
    CONST IDENTIFIER = 'EMP';

    public function get_code()
    {
        return $this->get_synchronization()->get_code() . '_' . self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Personeel';
    }

    public function get_description()
    {
        return 'Opgelet! De gebruikers in deze groep worden over alle academiejaren heen geactualiseerd!';
    }

    public function get_children()
    {
        $children = array();
        
        $children[] = GroupSynchronization::factory(
            '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Employee\Admin', 
            $this);
        $children[] = GroupSynchronization::factory(
            '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Employee\Teacher', 
            $this);
        
        return $children;
    }
}
