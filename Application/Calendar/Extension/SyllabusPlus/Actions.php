<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;

class Actions extends \Chamilo\Application\Calendar\Actions
{

    public function get(Application $application)
    {
        $buttonGroup = new ButtonGroup();

        if ($application->getUser()->get_platformadmin() ||
             $application->getUser()->get_status() == User :: STATUS_TEACHER)
        {
            $userBrowserUrl = new Redirect(
                array(
                    Application :: PARAM_CONTEXT => __NAMESPACE__,
                    Manager :: PARAM_ACTION => Manager :: ACTION_USER_BROWSER));

            $buttonGroup->addButton(
                new Button(
                    Translation :: get('UserBrowserComponent'),
                    Theme :: getInstance()->getImagePath(__NAMESPACE__, 'Tab/UserBrowser'),
                    $userBrowserUrl->getUrl(),
                    Button :: DISPLAY_ICON));
        }

        $browserUrl = new Redirect(
            array(Application :: PARAM_CONTEXT => __NAMESPACE__, Manager :: PARAM_ACTION => Manager :: ACTION_BROWSER));

        $buttonGroup->addButton(
            new Button(
                Translation :: get('TypeName'),
                Theme :: getInstance()->getImagePath(__NAMESPACE__, 'Logo/22'),
                $browserUrl->getUrl(),
                Button :: DISPLAY_ICON));

        return array($buttonGroup);
    }
}