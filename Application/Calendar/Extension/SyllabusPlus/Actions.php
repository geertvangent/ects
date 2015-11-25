<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Theme;

class Actions extends \Chamilo\Application\Calendar\Actions
{

    public function get()
    {
        $tabs = array();

        $syllabusPlusUserBrowserUrl = new Redirect(
            array(
                Application :: PARAM_CONTEXT => __NAMESPACE__,
                \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTION => \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: ACTION_USER_BROWSER));

        $tabs[] = new DynamicVisualTab(
            'SyllabusPlusUserBrowser',
            Translation :: get('syllabusPlusUserBrowser'),
            Theme :: getInstance()->getImagePath(__NAMESPACE__, 'Tab/SyllabusPlusUserBrowser'),
            $syllabusPlusUserBrowserUrl->getUrl(),
            false,
            false,
            DynamicVisualTab :: POSITION_RIGHT,
            DynamicVisualTab :: DISPLAY_BOTH_SELECTED);

        return $tabs;
    }
}