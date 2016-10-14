<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current;

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

    public function get_children()
    {
        $yearsQueryString = implode(
            '\', \'',
            $this->get_synchronization()->get_synchronization()->get_synchronization()->get_year());

        var_dump($yearsQueryString);
        exit();

        $query = 'SELECT DISTINCT code, name FROM [INFORDATSYNC].[dbo].[v_sync_current_faculty] WHERE year IN (\'' .
             $yearsQueryString . '\')';
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
