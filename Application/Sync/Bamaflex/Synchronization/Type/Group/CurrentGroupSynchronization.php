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

    public function get_description()
    {
        return 'Opgelet! De gebruikers in deze groep worden over alle academiejaren heen geactualiseerd!';
    }

    public function get_children()
    {
        $yearsQueryString = implode('\', \'', self::getCurrentYears());
        
        $query = 'SELECT DISTINCT code, name FROM [INFORDATSYNC].[dbo].[v_sync_current_faculty] WHERE year IN (\'' .
             $yearsQueryString . '\')';
        $faculties = $this->get_result($query);
        
        $children = array();
        
        while ($faculty = $faculties->next_result())
        {
            $children[] = GroupSynchronization::factory(
                '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Faculty', 
                $this, 
                $faculty);
        }
        
        $children[] = GroupSynchronization::factory(
            '\Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\CentralAdministration', 
            $this);
        
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

    public static function getCurrentYears()
    {
        $currentTimestamp = new \DateTime();
        $currentYear = $currentTimestamp->format('Y');
        
        $referenceTimestampPreviousYear = new \DateTime(
            '15-10-' . $currentYear . '00:00:00', 
            new \DateTimeZone('Europe/Brussels'));
        
        $referenceTimestampNextYear = new \DateTime(
            '01-07-' . $currentYear . '00:00:00', 
            new \DateTimeZone('Europe/Brussels'));
        
        $years = array();
        
        if ($currentTimestamp->getTimestamp() >= $referenceTimestampNextYear->getTimestamp())
        {
            $years[] = $currentYear . '-' . substr((string) ($currentYear + 1), 2, 2);
        }
        
        if ($currentTimestamp->getTimestamp() <= $referenceTimestampPreviousYear->getTimestamp())
        {
            $years[] = ($currentYear - 1) . '-' . substr((string) ($currentYear), 2, 2);
        }
        
        return $years;
    }
}
