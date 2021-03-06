<?php
namespace Ehb\Application\Discovery\Rendition;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Discovery\Module;

abstract class RenditionImplementation extends AbstractRenditionImplementation
{

    public static function launch(Module $module, $format, $view, $context)
    {
        return self::factory($module, $format, $view, $context)->render();
    }

    public static function factory(Module $module, $format, $view, $context)
    {
        $namespace = ClassnameUtilities::getInstance()->getNamespaceFromObject($module);
        $class = $namespace . '\Rendition\\' . StringUtilities::getInstance()->createString($format)->upperCamelize() .
             '\\' . StringUtilities::getInstance()->createString($format)->upperCamelize() .
             StringUtilities::getInstance()->createString($view)->upperCamelize() . 'RenditionImplementation';
        
        if (! class_exists($class, true))
        {
            return new DummyRenditionImplementation($context, $module, $format, $view);
        }
        else
        {
            return new $class($context, $module);
        }
    }

    public function get_data_manager()
    {
        return $this->get_module()->get_data_manager();
    }

    public function get_module_instance()
    {
        return $this->get_module()->get_module_instance();
    }

    public function get_instance_url($instance_id, $instance_parameters)
    {
        return $this->get_module()->get_instance_url($instance_id, $instance_parameters);
    }

    public function get_module_parameters()
    {
        return $this->get_module()->get_module_parameters();
    }

    public function module_parameters()
    {
        return $this->get_module()->module_parameters();
    }

    public function has_data($parameters)
    {
        return $this->get_module()->has_data($parameters);
    }

    public function get_application()
    {
        return $this->get_module()->get_application();
    }

    public function get_module_link($type, $user_id)
    {
        return $this->get_module()->get_module_link($type, $user_id);
    }
}
