<?php
namespace application\discovery\module\person\implementation\chamilo;
use common\libraries\Utilities;

use group\GroupRelUser;

use group\DefaultGroupRelUserTableCellRenderer;

use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\Toolbar;
use common\libraries\Theme;
use user\userManager;

class GroupRelUserBrowserTableCellRenderer extends DefaultGroupRelUserTableCellRenderer
{
    /**
     * The repository browser component
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
    function render_cell($column, $groupreluser)
    {
        if ($column === GroupRelUserBrowserTableColumnModel :: get_modification_column())
        {
            return $this->get_modification_links($groupreluser);
        }
        
        // Add special features here
        switch ($column->get_name())
        {
            // Exceptions that need post-processing go here ...
            case GroupRelUser :: PROPERTY_USER_ID :
                $user_id = parent :: render_cell($column, $groupreluser);
                $user = UserManager :: retrieve_user($user_id);
                return $user->get_fullname();
        }
        return parent :: render_cell($column, $groupreluser);
    }

    private function get_modification_links($groupreluser)
    {
        $toolbar = new Toolbar();
        
        $data_source = $this->browser->get_module_instance()->get_setting('data_source');
        $profile_module_instance = \application\discovery\Module :: exists('application\discovery\module\profile\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        if ($profile_module_instance)
        {
            $parameters = new \application\discovery\module\profile\Parameters($groupreluser->get_user_id());
            
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