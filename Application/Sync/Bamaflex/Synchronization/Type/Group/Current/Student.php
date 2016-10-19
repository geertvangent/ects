<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\CurrentGroupSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Student extends GroupSynchronization
{
    CONST IDENTIFIER = 'STU';

    public function get_code()
    {
        return $this->get_synchronization()->get_code() . '_' . self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Studenten';
    }

    public function get_description()
    {
        return 'Opgelet! De gebruikers in deze groep worden over alle academiejaren heen geactualiseerd!';
    }

    public function get_children()
    {
        $facultyGroupSynchronization = $this->get_synchronization();

        $yearsQueryString = implode('\', \'', CurrentGroupSynchronization::getCurrentYears());
        $facultyCode = $facultyGroupSynchronization->get_parameter(Faculty::RESULT_PROPERTY_CODE);

        $query = 'SELECT DISTINCT code, name FROM [INFORDATSYNC].[dbo].[v_sync_current_training] WHERE year IN (\'' .
             $yearsQueryString . '\') AND faculty_code = \'' . $facultyCode . '\' AND type != 16';
        $trainings = $this->get_result($query);

        $children = array();

        while ($training = $trainings->next_result())
        {
            $children[] = GroupSynchronization::factory(
                '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Student\Training',
                $this,
                $training);
        }

        return $children;
    }
}
