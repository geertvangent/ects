<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use libraries\Request;

class Module extends \application\discovery\module\training_info\Module
{
    const PARAM_SOURCE = 'source';
    const PARAM_TAB = 'tab';
    const PARAM_SUBTAB = 'subtab';
    const TAB_GOALS = 1;
    const TAB_OPTIONS = 2;
    const TAB_TRAJECTORIES = 3;
    const TAB_COURSES = 4;
    const TAB_OPTION_CHOICES = 1;
    const TAB_OPTION_MAJORS = 2;
    const TAB_OPTION_PACKAGES = 3;

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
    {
        $current_tab = Request :: get(Module :: PARAM_TAB);
        if (is_null($current_tab))
        {
            $current_tab = self :: TAB_GOALS;
        }
        return new Parameters(
            Request :: get(self :: PARAM_TRAINING_ID), 
            Request :: get(self :: PARAM_SOURCE), 
            $current_tab);
    }
}
