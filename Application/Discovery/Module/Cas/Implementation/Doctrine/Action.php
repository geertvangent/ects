<?php
namespace Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Ehb\Application\Discovery\DiscoveryItem;

class Action extends DiscoveryItem
{
    const AUTHENTICATION_SUCCESS = 1;
    const TICKET_GRANTING_TICKET_CREATED = 2;
    const TICKET_GRANTING_TICKET_DESTROYED = 3;
    const SERVICE_TICKET_CREATED = 4;
    const SERVICE_TICKET_VALIDATED = 5;
    const AUTHENTICATION_FAILED = 6;
    const TICKET_GRANTING_TICKET_NOT_CREATED = 7;
    const SERVICE_TICKET_NOT_CREATED = 8;
    const SERVICE_TICKET_VALIDATE_FAILED = 9;

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    public function get_name()
    {
        switch ($this->get_id())
        {
            case self::AUTHENTICATION_SUCCESS :
                return 'Login';
                break;
            case self::TICKET_GRANTING_TICKET_CREATED :
                return 'TgtCreated';
                break;
            case self::TICKET_GRANTING_TICKET_DESTROYED :
                return 'TgtDestroyed';
                break;
            case self::SERVICE_TICKET_CREATED :
                return 'ApplicationLogIn';
                break;
            case self::SERVICE_TICKET_VALIDATED :
                return 'ApplicationUse';
                break;
            case self::AUTHENTICATION_FAILED :
                return 'LogInFailed';
                break;
            case self::TICKET_GRANTING_TICKET_NOT_CREATED :
                return 'TgtNotCreated';
                break;
            case self::SERVICE_TICKET_NOT_CREATED :
                return 'ApplicationLogInFailed';
                break;
            case self::SERVICE_TICKET_VALIDATE_FAILED :
                return 'ApplicationUseFailed';
                break;
        }
    }
}
