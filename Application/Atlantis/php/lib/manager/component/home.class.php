<?php
namespace application\atlantis;

use common\libraries\DynamicSearchAction;

use common\libraries\DelegateComponent;
use common\libraries\DynamicAction;
use common\libraries\Translation;
use common\libraries\Theme;
use common\libraries\DynamicActionsTab;
use common\libraries\Utilities;
use common\libraries\DynamicTabsRenderer;

class HomeComponent extends Manager implements DelegateComponent
{

    function run()
    {
        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        // Role tab
        $namespace = \application\atlantis\role\Manager :: context();
        $actions = array();
        $actions[] = new DynamicSearchAction($namespace, $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_ROLE)));
        $actions[] = new DynamicAction(Translation :: get('BrowseRoles', null, $namespace), Translation :: get('BrowseRolesDescription', null, $namespace), Theme :: get_image_path($namespace) . 'admin/browse.png', $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_ROLE)));
        $actions[] = new DynamicAction(Translation :: get('CreateRole', null, $namespace), Translation :: get('CreateRoleDescription', null, $namespace), Theme :: get_image_path($namespace) . 'admin/create.png', $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_ROLE,
                \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_CREATE)));
        $tabs->add_tab(new DynamicActionsTab('role', Translation :: get('TypeName', null, $namespace), Theme :: get_image_path($namespace) . 'logo/22.png', $actions));

        // Application tab
        $namespace = \application\atlantis\application\Manager :: context();
        $actions = array();
        $actions[] = new DynamicSearchAction($namespace, $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_APPLICATION)));
        $actions[] = new DynamicAction(Translation :: get('BrowseApplications', null, $namespace), Translation :: get('BrowseApplicationsDescription', null, $namespace), Theme :: get_image_path($namespace) . 'admin/browse.png', $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_APPLICATION)));
        $actions[] = new DynamicAction(Translation :: get('CreateApplication', null, $namespace), Translation :: get('CreateApplicationDescription', null, $namespace), Theme :: get_image_path($namespace) . 'admin/create.png', $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_APPLICATION,
                \application\atlantis\application\Manager :: PARAM_ACTION => \application\atlantis\application\Manager :: ACTION_CREATE)));
        $actions[] = new DynamicAction(Translation :: get('ListApplications', null, $namespace), Translation :: get('ListApplicationsRights', null, $namespace), Theme :: get_image_path($namespace) . 'admin/list.png', $this->get_url(array(
                self :: PARAM_ACTION => self :: ACTION_APPLICATION,
                \application\atlantis\application\Manager :: PARAM_ACTION => \application\atlantis\application\Manager :: ACTION_LIST)));
        $tabs->add_tab(new DynamicActionsTab('application', Translation :: get('TypeName', null, $namespace), Theme :: get_image_path($namespace) . 'logo/22.png', $actions));

        $this->display_header();
        echo $tabs->render();
        $this->display_footer();
    }
}
?>