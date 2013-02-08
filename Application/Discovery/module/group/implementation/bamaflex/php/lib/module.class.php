<?php
namespace application\discovery\module\group\implementation\bamaflex;

use common\libraries\Request;

class Module extends \application\discovery\module\group\Module
{
    const PARAM_SOURCE = 'source';

    private $cache_groups = array();

    function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_TRAINING_ID), Request :: get(self :: PARAM_SOURCE));
    }

    function get_groups_data($type)
    {
        if (! isset($this->cache_groups[$type]))
        {
            $groups = array();
            foreach ($this->get_groups() as $group)
            {
                if ($group->get_type() == $type)
                {
                    $groups[] = $group;
                }
            }

            $this->cache_groups[$type] = $groups;
        }
        return $this->cache_groups[$type];
    }

    function has_groups($type)
    {
        if ($type)
        {
            return count($this->get_groups_data($type));
        }
        else
        {
            return count($this->get_groups()) > 0;
        }
    }

    static function get_training_info_parameters()
    {
        $training_id = Request :: get(self :: PARAM_TRAINING_ID);
        $source = Request :: get(self :: PARAM_SOURCE);

        $parameter = new \application\discovery\module\training_info\implementation\bamaflex\Parameters();
        $parameter->set_training_id($training_id);

        if ($source)
        {
            $parameter->set_source($source);
        }

        return $parameter;
    }
}
