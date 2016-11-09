<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CodeComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $official_code = Request::get(self::PARAM_USER_USER_ID);
        
        if (is_numeric($official_code))
        {
            try
            {
                $user = \Chamilo\Core\User\Storage\DataManager::retrieve_user_by_official_code($official_code);
                
                if ($user instanceof User)
                {
                    $redirect = new Redirect(
                        array(
                            self::PARAM_CONTEXT => self::package(), 
                            self::PARAM_ACTION => self::ACTION_BROWSE_USER, 
                            self::PARAM_USER_USER_ID => $user->get_id()));
                    $redirect->toUrl();
                }
                else
                {
                    throw new \Exception(Translation::get('NoSuchUser'));
                }
            }
            catch (\Exception $exception)
            {
                throw new \Exception(Translation::get('NoSuchUser'));
            }
        }
        else
        {
            throw new \Exception(Translation::get('NoSuchUser'));
        }
    }
}
