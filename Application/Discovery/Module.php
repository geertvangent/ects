<?php
namespace Chamilo\Application\Discovery;

use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 *
 * @author Hans De Bisschop
 */
class Module
{

    /**
     *
     * @var Application
     */
    private $application;

    /**
     *
     * @var Instance
     */
    private $module_instance;

    /**
     *
     * @param $application Application
     * @param $module_instance Instance
     */
    public function __construct(Application $application,\Chamilo\Application\Discovery\Instance\DataClass\Instance $module_instance)
    {
        $this->application = $application;
        $this->module_instance = $module_instance;
    }

    /**
     *
     * @param $application Application
     * @param $module_instance Instance
     * @return Module
     */
    public static function factory(Application $application,\Chamilo\Application\Discovery\Instance\DataClass\Instance $module_instance)
    {
        $class = $module_instance->get_type() . '\\Module';
        
        return new $class($application, $module_instance);
    }

    /**
     *
     * @return string
     */
    public function render()
    {
        return '';
    }

    /**
     *
     * @return \application\discovery\instance\Instance
     */
    public function get_module_instance()
    {
        return $this->module_instance;
    }

    /**
     *
     * @return \application\discovery\Application
     */
    public function get_application()
    {
        return $this->application;
    }

    public static function exists($type, $settings)
    {
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
                \Chamilo\Application\Discovery\Instance\DataClass\Instance :: PROPERTY_TYPE), 
            new StaticConditionVariable($type));
        $conditions[] = new NotCondition(
            new EqualityCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
                    \Chamilo\Application\Discovery\Instance\DataClass\Instance :: PROPERTY_CONTENT_TYPE), 
                new StaticConditionVariable(\Chamilo\Application\Discovery\Instance\DataClass\Instance :: TYPE_DISABLED)));
        $condition = new AndCondition($conditions);
        
        $module_instances = \Chamilo\Application\Discovery\Instance\DataManager :: retrieves(
            \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
            new DataClassRetrievesParameters($condition));
        while ($module_instance = $module_instances->next_result())
        {
            if ($module_instance->has_matching_settings($settings))
            {
                return $module_instance;
            }
        }
        return false;
    }

    public function get_instance_url($instance_id, $instance_parameters)
    {
        $parameters = array();
        $parameters[Manager :: PARAM_MODULE_ID] = $instance_id;
        foreach ($instance_parameters->get_parameters() as $key => $value)
        {
            $parameters[$key] = $value;
        }
        return $this->get_application()->get_url($parameters);
    }

    public static function get_available_implementations()
    {
        return array();
    }

    public static function get_available_types()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            Path :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'module/', 
            Filesystem :: LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = '\\' . __NAMESPACE__ . '\module\\' . $module . '\Module';
            if (class_exists($namespace, true))
            {
                $types = array_merge($types, $namespace :: get_available_implementations());
            }
        }
        return $types;
    }

    public function get_packages_from_filesystem()
    {
        $types = array();
        
        $directories = Filesystem :: get_directory_content(
            Path :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'module/', 
            Filesystem :: LIST_DIRECTORIES, 
            false);
        
        foreach ($directories as $directory)
        {
            $types[] = __NAMESPACE__ . '\module\\' . $directory;
        }
        
        return $types;
    }

    public function get_type()
    {
        return \Chamilo\Application\Discovery\Instance\DataClass\Instance :: TYPE_DISABLED;
    }

    /**
     * Far from ideal and not really generic (because of the user) . .. but it'll have to do for now
     * 
     * @param $type string
     * @param $user user\User
     * @return \libraries\format\ToolbarItem NULL
     */
    public function get_module_link($type, $user_id, $check_data = true)
    {
        $module_instance = \Chamilo\Application\Discovery\Module :: exists($type);
        
        if ($module_instance)
        {
            $class_parameters = $type . '\Parameters';
            $parameters = new $class_parameters();
            $parameters->set_user_id($user_id);
            
            $module = Module :: factory($this->get_application(), $module_instance);
            
            $class_rights = $type . '\Rights';
            
            if (! $class_rights :: is_allowed($class_rights :: VIEW_RIGHT, $module_instance->get_id(), $parameters))
            {
                return new ToolbarItem(
                    Translation :: get(
                        'ModuleNotAvailable', 
                        array('MODULE' => Translation :: get('TypeName', null, $type))), 
                    Theme :: getInstance()->getImagePath($type) . 'logo/16_na.png', 
                    null, 
                    ToolbarItem :: DISPLAY_ICON);
            }
            else
            {
                if (($check_data && $module->has_data($parameters)) || ! $check_data)
                {
                    $url = $this->get_instance_url($module_instance->get_id(), $parameters);
                    return new ToolbarItem(
                        Translation :: get('TypeName', null, $type), 
                        Theme :: getInstance()->getImagePath($type) . 'logo/16.png', 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON);
                }
                else
                
                {
                    $url = $this->get_instance_url($module_instance->get_id(), $parameters);
                    return new ToolbarItem(
                        Translation :: get(
                            'ModuleHasNoData', 
                            array('MODULE' => Translation :: get('TypeName', null, $type))), 
                        Theme :: getInstance()->getImagePath($type) . 'logo/16_empty.png', 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON);
                }
            }
        }
        
        return null;
    }

    public function has_data($module_parameters)
    {
        return true;
    }

    public function get_data_manager()
    {
        return DataConnector :: get_instance($this->get_module_instance());
    }
}
