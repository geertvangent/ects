<?php
namespace application\discovery;

use libraries\Translation;
use libraries\PlatformSetting;
use libraries\Theme;
use libraries\Application;
use configuration\package\PackageList;
use configuration\DataManager;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class Manager extends Application
{
    const APPLICATION_NAME = 'discovery';
    const ACTION_VIEW = 'viewer';
    const ACTION_CODE = 'code';
    const ACTION_MODULE = 'module';
    const ACTION_DATA_SOURCE = 'data_source';
    const PARAM_USER_ID = 'user_id';
    const PARAM_MODULE_ID = 'module_id';
    const PARAM_OFFICIAL_CODE = 'official_code';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_DIRECTION = 'direction';
    const PARAM_DIRECTION_UP = 'up';
    const PARAM_DIRECTION_DOWN = 'down';
    const PARAM_FORMAT = 'format';
    const PARAM_VIEW = 'view';
    const DEFAULT_ACTION = self :: ACTION_VIEW;

    /**
     * Constructor
     * 
     * @param $user User The current user
     */
    public function __construct($user = null, $application = null)
    {
        parent :: __construct($user, $application);
        Theme :: set_theme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: DEFAULT_ACTION DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: DEFAULT_ACTION in the
     * context of this class - YourApplicationManager :: DEFAULT_ACTION in all other application classes
     */
    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    public static function get_installable_application_packages($include_installed = false)
    {
        $package_list = new PackageList(
            self :: context(), 
            Translation :: get('TypeName', null, __NAMESPACE__), 
            Theme :: get_image_path() . 'logo/16.png');
        
        $module_list = new PackageList(self :: context() . '\module', Translation :: get('Modules', null, __NAMESPACE__));
        
        foreach (Module :: get_packages_from_filesystem() as $module_type)
        {
            if (! DataManager :: get_registration($module_type) || $include_installed)
            {
                $module_list->add_package($module_type);
            }
            
            $module_class = '\\' . $module_type . '\Module';
            
            if (class_exists($module_class))
            {
                $module_implementations = $module_class :: get_available_implementations();
                
                if (count($module_implementations) > 0)
                {
                    $module_implementations_list = new PackageList(
                        $module_type, 
                        Translation :: get('TypeName', null, $module_type), 
                        Theme :: get_image_path($module_type) . 'logo/16.png');
                    
                    foreach ($module_implementations as $module_implementation)
                    {
                        $module_implementations_list->add_package($module_implementation);
                    }
                    
                    $module_list->add_child($module_implementations_list);
                }
            }
        }
        
        $package_list->add_child($module_list);
        
        if (! DataManager :: get_registration(self :: context() . '\data_source') || $include_installed)
        {
            $package_list->add_package(self :: context() . '\data_source');
        }
        
        if (! DataManager :: get_registration(self :: context() . '\instance') || $include_installed)
        {
            $package_list->add_package(self :: context() . '\instance');
        }
        
        return $package_list;
    }
}
