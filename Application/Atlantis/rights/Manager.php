<?php
namespace Chamilo\Application\Atlantis\Rights;

use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\DynamicVisualTab;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Format\Breadcrumb;

class Manager extends Application
{
    const PARAM_ACTION = 'access_rights_action';
    const PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID = 'location_entity_right_group_id';
    const ACTION_CREATE = 'creator';
    const ACTION_ACCESS = 'accessor';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const DEFAULT_ACTION = self :: ACTION_CREATE;

    public function __construct($user = null, $application = null)
    {
        parent :: __construct($user, $application);
        
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
