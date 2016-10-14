<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class CurrentGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CUR';

    public function get_code()
    {
        return self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Huidige gebruikers';
    }

    public function get_children()
    {
        $yearsQueryString = implode('\', \'', $this->get_synchronization()->get_year());

        $query = 'SELECT DISTINCT code, name FROM [INFORDATSYNC].[dbo].[v_sync_current_faculty] WHERE year IN (\'' .
             $yearsQueryString . '\')';
        $faculties = $this->get_result($query);

        $children = array();

        while ($faculty = $faculties->next_result())
        {
            $children[] = GroupSynchronization::factory('current_faculty', $this, $faculty);
        }

        $children[] = GroupSynchronization::factory('current_central_administration', $this);

        return $children;
    }

    public static function getEmployeeFacultyCode($studentFacultyCode)
    {
        $studentfacultyCodeMapping = array(
            'DANS' => array('COM', 'SAW', 'HTO'),
            'GEZLA' => array('GEZ', 'HOR'),
            'IWT' => array('IWT'),
            'KCB' => array('KCB'),
            'LER' => array('LER'),
            'RITS' => array('RIT'),
            'TTK' => 'TTK');

        if (isset($studentfacultyCodeMapping[$studentFacultyCode]))
        {
            return $studentfacultyCodeMapping[$studentFacultyCode];
        }
        else
        {
            return false;
        }
    }
}
