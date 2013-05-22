<?php
namespace application\atlantis\rights;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\DynamicVisualTab;
use common\libraries\Utilities;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\SubManager;

class Manager extends SubManager
{
    const PARAM_ACTION = 'quota_rights_action';
    const PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID = 'location_entity_right_group_id';
    const ACTION_CREATE = 'creator';
    const ACTION_ACCESS = 'accessor';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const DEFAULT_ACTION = self :: ACTION_CREATE;

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    public static function launch($application)
    {
        parent :: launch(null, $application);
    }

    public function get_tabs($current_tab, $content)
    {
        $tabs = new DynamicVisualTabsRenderer(Utilities :: get_classname_from_namespace(__NAMESPACE__, true), $content);
        
        $tabs->add_tab(
            new DynamicVisualTab(
                self :: ACTION_CREATE, 
                Translation :: get('Add'), 
                Theme :: get_image_path() . 'tab/' . self :: ACTION_CREATE . '.png', 
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)), 
                ($current_tab == self :: ACTION_CREATE ? true : false)));
        $tabs->add_tab(
            new DynamicVisualTab(
                self :: ACTION_ACCESS, 
                Translation :: get('GeneralAccess'), 
                Theme :: get_image_path() . 'tab/' . self :: ACTION_ACCESS . '.png', 
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ACCESS)), 
                ($current_tab == self :: ACTION_ACCESS ? true : false)));
        $tabs->add_tab(
            new DynamicVisualTab(
                self :: ACTION_BROWSE, 
                Translation :: get('Targets'), 
                Theme :: get_image_path() . 'tab/' . self :: ACTION_BROWSE . '.png', 
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_BROWSE)), 
                ($current_tab == self :: ACTION_BROWSE ? true : false)));
        return $tabs;
    }
}
