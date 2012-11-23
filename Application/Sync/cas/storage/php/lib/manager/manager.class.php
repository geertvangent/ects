<?php
namespace application\ehb_sync\cas\storage;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\DynamicVisualTab;
use common\libraries\Utilities;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\SubManager;

class Manager extends SubManager
{
    const PARAM_ACTION = 'account_action';
    const ACTION_BROWSE = 'browser';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

    static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    static function launch($application)
    {
        parent :: launch(null, $application);
    }
}
?>