<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use common\libraries\Request;

class Module extends \application\discovery\module\group_user\Module
{
    const PARAM_SOURCE = 'source';
    const PARAM_TYPE = 'type';

    function get_module_parameters()
    {
        return self :: module_parameters();
    }

    static function module_parameters()
    {
        $group_class_id = Request :: get(self :: PARAM_GROUP_CLASS_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $type = Request :: get(self :: PARAM_TYPE);
        $parameter = new Parameters();

        if ($group_class_id)
        {
            $parameter->set_group_class_id($group_class_id);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        if ($type)
        {
            $parameter->set_type($type);
        }
        return $parameter;
    }

    static function get_group_parameters()
    {
        $group_class_id = Request :: get(self :: PARAM_GROUP_CLASS_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $type = Request :: get(self :: PARAM_TYPE);

        $parameter = new \application\discovery\module\group_user\implementation\bamaflex\Parameters();
        $parameter->set_group_class_id($group_class_id);
        $parameter->set_type($type);

        if ($source)
        {
            $parameter->set_source($source);
        }

        return $parameter;
    }
}
?>