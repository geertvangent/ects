<?php
namespace application\discovery\module\person\implementation\chamilo;
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
        
        $data_source = $this->browser->get_module_instance()->get_setting('data_source');
        $profile_module_instance = \application\discovery\Module :: exists('application\discovery\module\profile\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        if ($profile_module_instance)
        {
            $parameters = new \application\discovery\module\profile\Parameters($user->get_id());
            
            $entities = array();
            $entities[\application\discovery\module\profile\implementation\bamaflex\RightsUserEntity :: ENTITY_TYPE] = \application\discovery\module\profile\implementation\bamaflex\RightsUserEntity :: get_instance();
            $entities[\application\discovery\module\profile\implementation\bamaflex\RightsPlatformGroupEntity :: ENTITY_TYPE] = \application\discovery\module\profile\implementation\bamaflex\RightsPlatformGroupEntity :: get_instance();
            
            if (! \application\discovery\module\profile\implementation\bamaflex\Rights :: get_instance()->module_is_allowed(\application\discovery\module\profile\implementation\bamaflex\Rights :: VIEW_RIGHT, $entities, $profile_module_instance->get_id(), $parameters))
            {
                $toolbar->add_item(new ToolbarItem(Translation :: get('View', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_details_na.png', null, ToolbarItem :: DISPLAY_ICON));
            }
            else
            {
                $url = $this->browser->get_instance_url($profile_module_instance->get_id(), $parameters);
                $toolbar->add_item(new ToolbarItem(Translation :: get('View', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_details.png', $url, ToolbarItem :: DISPLAY_ICON));
            }
        }
        //        $url = $this->browser->get_url(array(DiscoveryManager :: PARAM_ACTION => DiscoveryManager :: ACTION_VIEW,
        //                DiscoveryManager :: PARAM_USER_ID => $user->get_id()));
        //        $toolbar->add_item(new ToolbarItem(Translation :: get('View', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_details.png', $url, ToolbarItem :: DISPLAY_ICON));
        

        return $toolbar->as_html();
    }
}
?>
