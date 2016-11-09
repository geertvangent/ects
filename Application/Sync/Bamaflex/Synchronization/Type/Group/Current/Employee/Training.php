<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Employee;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\CurrentGroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Student
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Training extends \Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Training
{

    public function get_user_official_codes()
    {
        $yearsQueryString = implode('\', \'', CurrentGroupSynchronization::getCurrentYears());
        $trainingCode = $this->get_parameter(self::RESULT_PROPERTY_CODE);
        $userIdentifiers = array();
        
        $query = 'SELECT DISTINCT person_id FROM [INFORDATSYNC].[dbo].[v_sync_current_teacher] WHERE year IN (\'' .
             $yearsQueryString . '\') AND training_code = \'' . $trainingCode . '\'';
        
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $userIdentifiers[] = $user['person_id'];
        }
        
        return $userIdentifiers;
    }
}
