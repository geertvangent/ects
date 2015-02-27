<?php
namespace Ehb\Application\Discovery\Component;

use Ehb\Application\Discovery\Instance\DataClass\Instance;
use Ehb\Application\Discovery\Manager;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class DataSourceComponent extends Manager
{

    public function run()
    {
        $module_parameters = array();
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_MODULE;

        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('Modules'),
                Theme :: getInstance()->getCommonImagesPath() . 'action_config.png',
                $link));

        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;

        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('User'),
                Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'action_user.png',
                $link));

        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;

        $link = $this->get_url($module_parameters);

        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('Information'),
                Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'action_information.png',
                $link));

        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Ehb\Application\Discovery\DataSource\Manager :: context(),
            $this->get_user(),
            $this);
        $factory->run();
    }
}
