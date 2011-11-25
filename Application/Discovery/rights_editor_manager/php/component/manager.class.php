<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\ActionBarRenderer;
use common\libraries\ToolbarItem;

use common\libraries\Translation;
use common\libraries\Theme;

/**
 * Interface to manage rights
 */

class RightsEditorManagerManagerComponent extends RightsEditorManager
{

    function run()
    {
        $this->action_bar = $this->get_action_bar();
        $form = new ManageForm($this->get_url(), $this->get_available_rights());
        
        if ($form->validate())
        {
            $succes = $form->handle_rights();
            
            $message = Translation :: get($succes ? 'RightsChanged' : 'RightsNotChanged');
            $this->redirect($message, ! $succes, array(RightsEditorManager::PARAM_ACTION => RightsEditorManager::ACTION_EDIT_ADVANCED_RIGHTS));
        }
        
        $this->display_header();
        
        echo '<br />';
        echo $this->action_bar->as_html();
        
        echo '<div id="action_bar_browser" style="width:100%;">';
        $form->display();
        echo '</div>';
        
        $this->display_footer();
    }

    /**
     * Builds the actionbar;
     * @return ActionBarRenderer
     */
    function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        
        // Add the simple rights editor button
        $action_bar->add_common_action(new ToolbarItem(Translation :: get('AdvancedRightsEditor'), Theme :: get_common_image_path() . 'action_config.png', $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_EDIT_ADVANCED_RIGHTS)), ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        
        return $action_bar;
    }
}
?>