<?php
namespace Ehb\Application\Atlantis\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicAction;
use Chamilo\Libraries\Format\Tabs\DynamicActionsTab;
use Chamilo\Libraries\Format\Tabs\DynamicSearchAction;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\Manager;
use Ehb\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class HomeComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        SessionBreadcrumbs :: set(BreadcrumbTrail :: get_instance()->get_breadcrumbs());

        $renderer_name = ClassnameUtilities :: getInstance()->getClassnameFromObject($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        // Role tab
        $namespace = \Ehb\Application\Atlantis\Role\Manager :: context();
        $actions = array();
        $actions[] = new DynamicSearchAction(
            $namespace,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ROLE)));
        $actions[] = new DynamicAction(
            Translation :: get('BrowseRoles', null, $namespace),
            Translation :: get('BrowseRolesDescription', null, $namespace),
            Theme :: getInstance()->getImagesPath($namespace) . 'Admin/browse.png',
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ROLE)));
        if ($this->get_user()->is_platform_admin())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateRole', null, $namespace),
                Translation :: get('CreateRoleDescription', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_ROLE,
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_CREATE)));
        }
        $tabs->add_tab(
            new DynamicActionsTab(
                'role',
                Translation :: get('TypeName', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Logo/22.png',
                $actions));

        // Application tab
        $namespace = \Ehb\Application\Atlantis\Application\Manager :: context();
        $actions = array();
        $actions[] = new DynamicSearchAction(
            $namespace,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_APPLICATION)));
        $actions[] = new DynamicAction(
            Translation :: get('BrowseApplications', null, $namespace),
            Translation :: get('BrowseApplicationsDescription', null, $namespace),
            Theme :: getInstance()->getImagesPath($namespace) . 'Admin/browse.png',
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_APPLICATION)));
        if ($this->get_user()->is_platform_admin())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateApplication', null, $namespace),
                Translation :: get('CreateApplicationDescription', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_APPLICATION,
                        \Ehb\Application\Atlantis\Application\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Application\Manager :: ACTION_CREATE)));
        }
        $tabs->add_tab(
            new DynamicActionsTab(
                'application',
                Translation :: get('TypeName', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Logo/22.png',
                $actions));

        // RoleEntity tab
        $namespace = \Ehb\Application\Atlantis\Role\Entity\Manager :: context();
        $actions = array();
        $actions[] = new DynamicAction(
            Translation :: get('BrowseRoleEntities', null, $namespace),
            Translation :: get('BrowseRoleEntitiesDescription', null, $namespace),
            Theme :: getInstance()->getImagesPath($namespace) . 'Admin/browse.png',
            $this->get_url(
                array(
                    self :: PARAM_ACTION => self :: ACTION_ROLE,
                    \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITY,
                    \Ehb\Application\Atlantis\Role\Entity\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entity\Manager :: ACTION_BROWSE)));

        if (\Ehb\Application\Atlantis\Rights\Rights :: get_instance()->access_is_allowed())
        {
            $actions[] = new DynamicAction(
                Translation :: get('CreateRoleEntity', null, $namespace),
                Translation :: get('CreateRoleEntityDescription', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Admin/create.png',
                $this->get_url(
                    array(
                        self :: PARAM_ACTION => self :: ACTION_ROLE,
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITY)));
        }

        $tabs->add_tab(
            new DynamicActionsTab(
                'role_entity',
                Translation :: get('TypeName', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Logo/22.png',
                $actions));

        // Entity type
        // $namespace = \application\atlantis\role\entity\Manager :: context();
        // $actions = array();
        // $actions[] = new DynamicAction(Translation :: get('BrowseUsersGroups', null, $namespace),
        // Translation :: get('BrowseUsersGroupsDescription', null, $namespace),
        // Theme :: getInstance()->getImagesPath($namespace) . 'admin/browse.png',
        // $this->get_url(
        // array(self :: PARAM_ACTION => self :: ACTION_ROLE,
        // \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY,
        // \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager ::
        // ACTION_BROWSE)));

        // $tabs->add_tab(new DynamicActionsTab('user_group', Translation :: get('UsersGroups'), Theme ::
        // get_image_path() . 'usersgroups.png', $actions));

        // Context tab
        $namespace = \Ehb\Application\Atlantis\Context\Manager :: context();
        $actions = array();
        $actions[] = new DynamicAction(
            Translation :: get('BrowseContexts', null, $namespace),
            Translation :: get('BrowseContextsDescription', null, $namespace),
            Theme :: getInstance()->getImagesPath($namespace) . 'Admin/browse.png',
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CONTEXT)));

        $tabs->add_tab(
            new DynamicActionsTab(
                'context',
                Translation :: get('TypeName', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Logo/22.png',
                $actions));

        if ($this->get_user()->is_platform_admin())
        {
            // Rights tab
            $namespace = \Ehb\Application\Atlantis\Rights\Manager :: context();
            $actions = array();
            $actions[] = new DynamicAction(
                Translation :: get('ConfigureRights', null, $namespace),
                Translation :: get('ConfigureRightsDescription', null, $namespace),
                Theme :: getInstance()->getImagesPath($namespace) . 'Admin/rights.png',
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_RIGHTS)));

            $tabs->add_tab(
                new DynamicActionsTab(
                    'rights',
                    Translation :: get('TypeName', null, $namespace),
                    Theme :: getInstance()->getImagesPath($namespace) . 'Logo/22.png',
                    $actions));
        }

        $html = array();

        $html[] = $this->render_header();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }
}
