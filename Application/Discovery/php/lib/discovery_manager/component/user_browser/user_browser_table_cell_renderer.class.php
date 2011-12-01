<?php
namespace application\discovery;

use user\DefaultUserTableCellRenderer;
use user\User;

use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Toolbar;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\PlatformSetting;
use common\libraries\Session;
use common\libraries\Path;

use reporting\ReportingManager;

/**
 * $Id: user_browser_table_cell_renderer.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * @package user.lib.user_manager.component.user_browser
 */
require_once dirname(__FILE__) . '/user_browser_table_column_model.class.php';
require_once Path :: get_user_path() . '/lib/user_table/default_user_table_cell_renderer.class.php';

/**
 * Cell renderer for the user object browser table
 */
class UserBrowserTableCellRenderer extends DefaultUserTableCellRenderer
{
    /**
     * The user browser component
     */
    private $browser;

    /**
     * Constructor
     * @param RepositoryManagerBrowserComponent $browser
     */
    function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    // Inherited
    function render_cell($column, $user)
    {
        if ($column === UserBrowserTableColumnModel :: get_modification_column())
        {
            return $this->get_modification_links($user);
        }
        
        return parent :: render_cell($column, $user);
    }

    /**
     * Gets the action links to display
     * @param $user The user for which the
     * action links should be returned
     * @return string A HTML representation of the action links
     */
    private function get_modification_links($user)
    {
        $toolbar = new Toolbar();
        
        $url = $this->browser->get_url(array(DiscoveryManager :: PARAM_ACTION => DiscoveryManager :: ACTION_VIEW, 
                DiscoveryManager :: PARAM_USER_ID => $user->get_id()));
        $toolbar->add_item(new ToolbarItem(Translation :: get('View', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_details.png', $url, ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
?>
