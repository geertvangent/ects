<?php
namespace application\ehb_sync\cas;

use common\libraries\EqualityCondition;
use application\ehb_sync\cas\data\Statistic;
use common\libraries\DataClassRetrievesParameters;
use application\ehb_sync\cas\storage\ComAuditTrail;
use common\libraries\DelegateComponent;

class StatisticsComponent extends Manager implements DelegateComponent
{

    private $action_map = array(
        'AUTHENTICATION_SUCCESS' => 1, 
        'TICKET_GRANTING_TICKET_CREATED' => 2, 
        'TICKET_GRANTING_TICKET_DESTROYED' => 3, 
        'SERVICE_TICKET_CREATED' => 4, 
        'SERVICE_TICKET_VALIDATED' => 5, 
        'AUTHENTICATION_FAILED' => 6, 
        'TICKET_GRANTING_TICKET_NOT_CREATED' => 7, 
        'SERVICE_TICKET_NOT_CREATED' => 8, 
        'SERVICE_TICKET_VALIDATE_FAILED' => 9);

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        try
        {
            echo '<pre>';
            echo '[STAT SYNC STARTED] ' . date('c', time()) . "\n";
            
            $parameters = new DataClassRetrievesParameters(null, 200000);
            
            $audit_trails = \application\ehb_sync\cas\storage\DataManager :: retrieves(
                ComAuditTrail :: class_name(), 
                $parameters);
            
            while ($audit_trail = $audit_trails->next_result())
            {
                if ($this->convert($audit_trail))
                {
                    $audit_trail->delete();
                }
            }
            
            echo '[  STAT SYNC ENDED] ' . date('c', time()) . "\n";
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Processing statistics failed';
        }
    }

    public function convert(ComAuditTrail $audit_trail)
    {
        $statistic = new Statistic();
        $statistic->set_action_id($this->action_map[$audit_trail->get_action()]);
        
        if (in_array(
            $statistic->get_action_id(), 
            array(
                Statistic :: ACTION_AUTHENTICATION_SUCCESS, 
                Statistic :: ACTION_TICKET_GRANTING_TICKET_CREATED, 
                Statistic :: ACTION_AUTHENTICATION_FAILED, 
                Statistic :: ACTION_TICKET_GRANTING_TICKET_NOT_CREATED)))
        {
            $email_address = trim(str_replace(']', '', str_replace('[username: ', '', $audit_trail->get_user())));
            $statistic->set_user($email_address);
            $user = \user\DataManager :: retrieve(
                \user\User :: class_name(), 
                new EqualityCondition(\user\User :: PROPERTY_USERNAME, $email_address));
            if ($user instanceof \user\User)
            {
                $statistic->set_person_id($user->get_official_code());
            }
        }
        elseif (in_array(
            $statistic->get_action_id(), 
            array(Statistic :: ACTION_SERVICE_TICKET_CREATED, Statistic :: ACTION_SERVICE_TICKET_NOT_CREATED)))
        {
            $email_address = trim($audit_trail->get_user());
            $statistic->set_user($email_address);
            $user = \user\DataManager :: retrieve(
                \user\User :: class_name(), 
                new EqualityCondition(\user\User :: PROPERTY_USERNAME, $email_address));
            if ($user instanceof \user\User)
            {
                $statistic->set_person_id($user->get_official_code());
            }
        }
        else
        {
            $statistic->set_user(null);
            $statistic->set_person_id(null);
        }
        
        if (in_array(
            $statistic->get_action_id(), 
            array(Statistic :: ACTION_SERVICE_TICKET_CREATED, Statistic :: ACTION_SERVICE_TICKET_NOT_CREATED)))
        {
            $statistic->set_application_id(Statistic :: application_string_to_id($audit_trail->get_resource()));
            
            if ($statistic->get_application_id() === false)
            {
                return false;
            }
        }
        else
        {
            $statistic->set_application_id(null);
        }
        
        $statistic->set_date($audit_trail->get_date());
        
        if (! $statistic->create())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
