<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Theme;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Chamilo\Core\User\Storage\DataClass\User;

class Actions extends \Chamilo\Application\Calendar\Actions
{

    public function get(Application $application)
    {
        $tabs = array();

        if ($application->getUser()->get_platformadmin() ||
             $application->getUser()->get_status() == User :: STATUS_TEACHER)
        {
            $userBrowserUrl = new Redirect(
                array(
                    Application :: PARAM_CONTEXT => __NAMESPACE__,
                    Manager :: PARAM_ACTION => Manager :: ACTION_USER_BROWSER));

            $tabs[] = new DynamicVisualTab(
                'SyllabusPlusUserBrowser',
                Translation :: get('UserBrowserComponent'),
                Theme :: getInstance()->getImagePath(__NAMESPACE__, 'Tab/UserBrowser'),
                $userBrowserUrl->getUrl(),
                false,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED);
        }

        $browserUrl = new Redirect(
            array(Application :: PARAM_CONTEXT => __NAMESPACE__, Manager :: PARAM_ACTION => Manager :: ACTION_BROWSER));

        $tabs[] = new DynamicVisualTab(
            'SyllabusPlusBrowser',
            Translation :: get('TypeName'),
            Theme :: getInstance()->getImagePath(__NAMESPACE__, 'Logo/22'),
            $browserUrl->getUrl(),
            false,
            false,
            DynamicVisualTab :: POSITION_RIGHT,
            DynamicVisualTab :: DISPLAY_BOTH_SELECTED);

        return $tabs;
    }
}