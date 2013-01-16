<?php
namespace application\discovery\module\group_user;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;
use application\discovery\SortableTable;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    const PARAM_GROUP_CLASS_ID = 'group_class_id';

    /**
     *
     * @var multitype:\application\discovery\module\group_user\GroupUser
     */
    private $group_user;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_GROUP_CLASS_ID));
    }

    /**
     *
     * @return multitype:\application\discovery\module\group_user\GroupUser
     */
    function get_group_user()
    {
        if (! isset($this->group_user))
        {
            $this->group_user = $this->get_data_manager()->retrieve_group_users($this->get_group_user_parameters());
        }
        return $this->group_user;
    }
    
    /*
     * (non-PHPdoc) @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_DETAILS;
    }

    static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
                Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
?>