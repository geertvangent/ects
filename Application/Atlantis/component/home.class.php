<?php
namespace application\atlantis;

use libraries\format\BreadcrumbTrail;
use libraries\format\DynamicSearchAction;
use libraries\architecture\DelegateComponent;
use libraries\format\DynamicAction;
use libraries\platform\translation\Translation;
use libraries\format\theme\Theme;
use libraries\format\DynamicActionsTab;
use libraries\utilities\Utilities;
use libraries\format\DynamicTabsRenderer;

class HomeComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        SessionBreadcrumbs :: set(BreadcrumbTrail :: get_instance()->get_breadcrumbs());

        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        // Role tab
        $namespace = \application\atlantis\role\Manager :: context();
        $actions = array();
        $actions[] = new DynamicSearchAction(
            $namespace,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ROLE)));
        $actions[] = new DynamicAction(
            Translation :: get('BrowseRoles', null, $namespace),
            Translation :: get('BrowseRolesDescription', null, $namespace),
            Theme :: get_image_path($namespace) . 'admin/browse.png',
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ROLE)));
        if ($this->get_user()->is_platform_admin())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateRole', null, $namespace),
                Translation :: get('CreateRoleDescription', null, $namespace),
                Theme :: get_image_path($namespace) . 'admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_ROLE,
                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_CREATE)));
        }
        $tabs->add_tab(
            new DynamicActionsTab(
                'role',
                Translation :: get('TypeName', null, $namespace),
                Theme :: get_image_path($namespace) . 'logo/22.png',
                $actions));

        // Application tab
        $namespace = \application\atlantis\application\Manager :: context();
        $actions = array();
        $actions[] = new DynamicSearchAction(
            $namespace,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_APPLICATION)));
        $actions[] = new DynamicAction(
            Translation :: get('BrowseApplications', null, $namespace),
            Translation :: get('BrowseApplicationsDescription', null, $namespace),
            Theme :: get_image_path($namespace) . 'admin/browse.png',
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_APPLICATION)));
        if ($this->get_user()->is_platform_admin())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateApplication', null, $namespace),
                Translation :: get('CreateApplicationDescription', null, $namespace),
                Theme :: get_image_path($namespace) . 'admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_APPLICATION,
                        \application\atlantis\application\Manager :: PARAM_ACTION => \application\atlantis\application\Manager :: ACTION_CREATE)));
        }
        $tabs->add_tab(
            new DynamicActionsTab(
                'application',
                Translation :: get('TypeName', null, $namespace),
                Theme :: get_image_path($namespace) . 'logo/22.png',
                $actions));

        // RoleEntity tab
        $namespace = \application\atlantis\role\entity\Manager :: context();
        $actions = array();
        $actions[] = new DynamicAction(
            Translation :: get('BrowseRoleEntities', null, $namespace),
            Translation :: get('BrowseRoleEntitiesDescription', null, $namespace),
            Theme :: get_image_path($namespace) . 'admin/browse.png',
            $this->get_url(
                array(
                    self :: PARAM_ACTION => self :: ACTION_ROLE,
                    \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY,
                    \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager :: ACTION_BROWSE)));

        if (\application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateRoleEntity', null, $namespace),
                Translation :: get('CreateRoleEntityDescription', null, $namespace),
                Theme :: get_image_path($namespace) . 'admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_ROLE,
                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY)));
        }

        $tabs->add_tab(
            new DynamicActionsTab(
                'role_entity',
                Translation :: get('TypeName', null, $namespace),
                Theme :: get_image_path($namespace) . 'logo/22.png',
                $actions));

        // Entity type
        // $namespace = \application\atlantis\role\entity\Manager :: context();
        // $actions = array();
        // $actions[] = new DynamicAction(Translation :: get('BrowseUsersGroups', null, $namespace),
        // Translation :: get('BrowseUsersGroupsDescription', null, $namespace),
        // Theme :: get_image_path($namespace) . 'admin/browse.png',
        // $this->get_url(
        // array(self :: PARAM_ACTION => self :: ACTION_ROLE,
        // \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY,
        // \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager ::
        // ACTION_BROWSE)));

        // $tabs->add_tab(new DynamicActionsTab('user_group', Translation :: get('UsersGroups'), Theme ::
        // get_image_path() . 'usersgroups.png', $actions));

        // Context tab
        $namespace = \application\atlantis\context\Manager :: context();
        $actions = array();
        $actions[] = new DynamicAction(
            Translation :: get('BrowseContexts', null, $namespace),
            Translation :: get('BrowseContextsDescription', null, $namespace),
            Theme :: get_image_path($namespace) . 'admin/browse.png',
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CONTEXT)));

        $tabs->add_tab(
            new DynamicActionsTab(
                'context',
                Translation :: get('TypeName', null, $namespace),
                Theme :: get_image_path($namespace) . 'logo/22.png',
                $actions));

        if ($this->get_user()->is_platform_admin())
        {
            // Rights tab
            $namespace = \application\atlantis\rights\Manager :: context();
            $actions = array();
            $actions[] = new DynamicAction(
                Translation :: get('ConfigureRights', null, $namespace),
                Translation :: get('ConfigureRightsDescription', null, $namespace),
                Theme :: get_image_path($namespace) . 'admin/rights.png',
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_RIGHTS)));

            $tabs->add_tab(
                new DynamicActionsTab(
                    'rights',
                    Translation :: get('TypeName', null, $namespace),
                    Theme :: get_image_path($namespace) . 'logo/22.png',
                    $actions));
        }

        $this->display_header();
        echo $tabs->render();
        $this->display_footer();
    }
}
