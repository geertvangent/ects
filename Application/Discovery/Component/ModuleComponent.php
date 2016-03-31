<?php
namespace Ehb\Application\Discovery\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Manager;

class ModuleComponent extends Manager
{

    public function run()
    {
        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;

        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('User'),
                Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'Action/User.png',
                $link));

        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;

        $link = $this->get_url($module_parameters);

        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('Information'),
                Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'Action/Information.png',
                $link));

        $factory = new ApplicationFactory(
            \Ehb\Application\Discovery\Instance\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        $factory->run();
    }
}
