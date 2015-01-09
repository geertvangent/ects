<?php
namespace Chamilo\Application\Atlantis\Component;

use Chamilo\Libraries\Format\BreadcrumbTrail;
use Chamilo\Libraries\Format\DynamicSearchAction;
use Chamilo\Libraries\Architecture\DelegateComponent;
use Chamilo\Libraries\Format\DynamicAction;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Format\DynamicActionsTab;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\DynamicTabsRenderer;

class HomeComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        SessionBreadcrumbs :: set(BreadcrumbTrail :: get_instance()->get_breadcrumbs());

        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        // Role tab
        $namespace = \Chamilo\Application\Atlantis\Role\Manager :: context();
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
                        \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Role\Manager :: ACTION_CREATE)));
        }
        $tabs->add_tab(
            new DynamicActionsTab(
                'role',
                Translation :: get('TypeName', null, $namespace),
                Theme :: get_image_path($namespace) . 'logo/22.png',
                $actions));

        // Application tab
        $namespace = \Chamilo\Application\Atlantis\Application\Manager :: context();
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
                        \Chamilo\Application\Atlantis\Application\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Application\Manager :: ACTION_CREATE)));
        }
        $tabs->add_tab(
            new DynamicActionsTab(
                'application',
                Translation :: get('TypeName', null, $namespace),
                Theme :: get_image_path($namespace) . 'logo/22.png',
                $actions));

        // RoleEntity tab
        $namespace = \Chamilo\Application\Atlantis\Role\Entity\Manager :: context();
        $actions = array();
        $actions[] = new DynamicAction(
            Translation :: get('BrowseRoleEntities', null, $namespace),
            Translation :: get('BrowseRoleEntitiesDescription', null, $namespace),
            Theme :: get_image_path($namespace) . 'admin/browse.png',
            $this->get_url(
                array(
                    self :: PARAM_ACTION => self :: ACTION_ROLE,
                    \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Role\Manager :: ACTION_ENTITY,
                    \Chamilo\Application\Atlantis\Role\Entity\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Role\Entity\Manager :: ACTION_BROWSE)));

        if (\Chamilo\Application\Atlantis\Rights\Rights :: get_instance()->access_is_allowed())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateRoleEntity', null, $namespace),
                Translation :: get('CreateRoleEntityDescription', null, $namespace),
                Theme :: get_image_path($namespace) . 'admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_ROLE,
                        \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Role\Manager :: ACTION_ENTITY)));
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
        $namespace = \Chamilo\Application\Atlantis\Context\Manager :: context();
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
            $namespace = \Chamilo\Application\Atlantis\Rights\Manager :: context();
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
