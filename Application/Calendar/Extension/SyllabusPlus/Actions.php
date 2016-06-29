<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\SplitDropdownButton;
use Chamilo\Libraries\Format\Structure\ActionBar\SubButton;
use Chamilo\Libraries\Format\Structure\Glyph\BootstrapGlyph;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Actions implements \Chamilo\Application\Calendar\ActionsInterface
{

    /**
     *
     * @see \Chamilo\Application\Calendar\ActionsInterface::getPrimary()
     */
    public function getPrimary(Application $application)
    {
        return array();
    }

    /**
     *
     * @see \Chamilo\Application\Calendar\ActionsInterface::getAdditional()
     */
    public function getAdditional(Application $application)
    {
        $buttons = array();

        $browserUrl = new Redirect(
            array(Application::PARAM_CONTEXT => __NAMESPACE__, Manager::PARAM_ACTION => Manager::ACTION_BROWSER));

        if ($application->getUser()->get_platformadmin() || $application->getUser()->get_status() == User::STATUS_TEACHER)
        {
            $splitDropdownButton = new SplitDropdownButton(
                Translation::get('TypeName', null, __NAMESPACE__),
                new BootstrapGlyph('time'),
                $browserUrl->getUrl());
            $splitDropdownButton->setDropdownClasses('dropdown-menu-right');

            $userBrowserUrl = new Redirect(
                array(
                    Application::PARAM_CONTEXT => __NAMESPACE__,
                    Manager::PARAM_ACTION => Manager::ACTION_USER_BROWSER));

            $splitDropdownButton->addSubButton(
                new SubButton(
                    Translation::get('UserBrowserComponent'),
                    null,
                    $userBrowserUrl->getUrl(),
                    Button::DISPLAY_LABEL));

            $groupUrl = new Redirect(
                array(Application::PARAM_CONTEXT => __NAMESPACE__, Manager::PARAM_ACTION => Manager::ACTION_GROUP));

            $splitDropdownButton->addSubButton(
                new SubButton(
                    Translation::get(Manager::ACTION_GROUP . 'Component'),
                    null,
                    $groupUrl->getUrl(),
                    Button::DISPLAY_LABEL));

            $locationUrl = new Redirect(
                array(Application::PARAM_CONTEXT => __NAMESPACE__, Manager::PARAM_ACTION => Manager::ACTION_LOCATION));

            $splitDropdownButton->addSubButton(
                new SubButton(
                    Translation::get(Manager::ACTION_LOCATION . 'Component'),
                    null,
                    $locationUrl->getUrl(),
                    Button::DISPLAY_LABEL));

            $buttons[] = $splitDropdownButton;
        }
        else
        {
            $buttons[] = new Button(
                Translation::get('TypeName', null, __NAMESPACE__),
                new BootstrapGlyph('time'),
                $browserUrl->getUrl());
        }

        return $buttons;
    }
}