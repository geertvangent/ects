<?php
namespace application\discovery\module\person\implementation\chamilo;

use common\libraries\Toolbar;
use common\libraries\NewObjectTableCellRendererActionsColumnSupport;
use common\libraries\NewObjectTableCellRenderer;

/**
 * $Id: user_browser_table_cell_renderer.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 *
 * @package user.lib.user_manager.component.user_browser
 */

/**
 * Cell renderer for the user object browser table
 */
class UserBrowserTableCellRenderer extends NewObjectTableCellRenderer implements
        NewObjectTableCellRendererActionsColumnSupport
{

    function get_object_actions($user)
    {
        $toolbar = new Toolbar();

        $profile_link = $this->get_component()->get_module_link(
                'application\discovery\module\profile\implementation\bamaflex', $user->get_id());
        if ($profile_link)
        {
            $toolbar->add_item($profile_link);
        }

        $career_link = $this->get_component()->get_module_link(
                'application\discovery\module\career\implementation\bamaflex', $user->get_id());
        if ($career_link)
        {
            $toolbar->add_item($career_link);
        }

        return $toolbar->as_html();
    }
}
