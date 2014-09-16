<?php
namespace application\atlantis\rights;

use libraries\Theme;
use libraries\Translation;
use libraries\DynamicVisualTab;
use libraries\Utilities;
use libraries\DynamicVisualTabsRenderer;
use libraries\SubManager;
use application\atlantis\SessionBreadcrumbs;
use libraries\Breadcrumb;

class Manager extends SubManager
{
    const PARAM_ACTION = 'access_rights_action';
    const PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID = 'location_entity_right_group_id';
    const ACTION_CREATE = 'creator';
    const ACTION_ACCESS = 'accessor';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const DEFAULT_ACTION = self :: ACTION_CREATE;

    public function __construct($parent)
    {
        parent :: __construct($parent);
        
        SessionBreadcrumbs :: add(new Breadcrumb($this->get_url(), Translation :: get('TypeName')));
    }

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
