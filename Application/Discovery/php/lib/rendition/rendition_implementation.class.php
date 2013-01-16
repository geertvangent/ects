<?php
namespace application\discovery;

use common\libraries\Utilities;

abstract class RenditionImplementation extends AbstractRenditionImplementation
{

    static function launch(Module $module, $format, $view, $context)
    {
        return self :: factory($module, $format, $view, $context)->render();
    }

    static function factory(Module $module, $format, $view, $context)
    {
        $namespace = Utilities :: get_namespace_from_object($module);
        $class = $namespace . '\\' . Utilities :: underscores_to_camelcase($format) . Utilities :: underscores_to_camelcase(
                $view) . 'RenditionImplementation';

        if (! class_exists($class, true))
        {
            return new DummyRenditionImplementation($context, $module, $format, $view);
        }
        else
        {
            return new $class($context, $module);
        }
    }

    function get_module_instance()
    {
        return $this->get_module()->get_module_instance();
    }

    function get_rights_url($instance_id, $instance_parameters)
    {
        return $this->get_module()->get_rights_url($instance_id, $instance_parameters);
    }

    function get_instance_url($instance_id, $instance_parameters)
    {
        return $this->get_module()->get_instance_url($instance_id, $instance_parameters);
    }

    function get_module_parameters()
    {
        return $this->get_module()->get_module_parameters();
    }

    function has_data($parameters)
    {
        return $this->get_module()->has_data($parameters);
    }
}
?>