<?php
namespace Chamilo\Application\Discovery\Component;

use Chamilo\Application\Discovery\Instance\DataClass\Instance;
use Chamilo\Application\Discovery\Manager;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;

class DataSourceComponent extends Manager
{

    public function run()
    {
        $module_parameters = array();
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_MODULE;

        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(Translation :: get('Modules'), Theme :: getInstance()->getCommonImagePath() . 'action_config.png', $link));

        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;

        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(Translation :: get('User'), Theme :: getInstance()->getImagePath() . 'action_user.png', $link));

        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;

        $link = $this->get_url($module_parameters);

        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('Information'),
                Theme :: getInstance()->getImagePath() . 'action_information.png',
                $link));

        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Chamilo\Application\Discovery\DataSource\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
