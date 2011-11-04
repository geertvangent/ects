<?php
namespace application\discovery;

use common\libraries\NotCondition;

use common\libraries\EqualityCondition;
use common\libraries\AndCondition;

use common\libraries\Application;

/**
 * @author Hans De Bisschop
 *
 */
class Module
{	
    /**
     * @var Application
     */
    private $application;
    
    /**
     * @var ModuleInstance
     */
    private $module_instance;

    /**
     * @param Application $application
     * @param ModuleInstance $module_instance
     */
    function __construct(Application $application, ModuleInstance $module_instance)
    {
        $this->application = $application;
        $this->module_instance = $module_instance;
    }
    
    

    /**
     * @param Application $application
     * @param ModuleInstance $module_instance
     * @return Module
     */
    static public function factory(Application $application, ModuleInstance $module_instance)
    {
        $class = $module_instance->get_type() . '\\Module';
        return new $class($application, $module_instance);
    }

    /**
     * @return string
     */
    function render()
    {
        return '';
    }

    /**
     * @return \application\discovery\ModuleInstance
     */
    function get_module_instance()
    {
        return $this->module_instance;
    }

    /**
     * @return \application\discovery\Application
     */
    function get_application()
    {
        return $this->application;
    }

    static function exists($type, $settings)
    {
        $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_TYPE, $type);
        $conditions[] = new NotCondition(new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, ModuleInstance::TYPE_DISABLED));
        $condition = new AndCondition($conditions);
        
        $module_instances = DiscoveryDataManager :: get_instance()->retrieve_module_instances($condition);
        while ($module_instance = $module_instances->next_result())
        {
            if ($module_instance->has_matching_settings($settings))
            {
                return $module_instance;
            }
        }
        return false;
    }

    function get_instance_url($instance_id, $instance_parameters)
    {
        $parameters = array();
        $parameters[DiscoveryManager :: PARAM_MODULE_ID] = $instance_id;
        foreach ($instance_parameters->get_parameters() as $key => $value)
        {
            $parameters[$key] = $value;
        }
        return $this->get_application()->get_url($parameters);
    }
}
?>