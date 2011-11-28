<?php
namespace application\discovery;

use common\libraries\ToolbarItem;

use common\libraries\SortableTableFromArray;

use common\libraries;

use admin\PackageInfo;
use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\Path;
use common\libraries\BreadcrumbTrail;
use common\libraries\Utilities;
use common\libraries\EqualityCondition;
use common\libraries\AndCondition;
use common\libraries\Theme;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Filesystem;

use common\extension\external_repository_manager\ExternalRepositoryManager;
use common\extension\video_conferencing_manager\VideoConferencingManager;

use DOMDocument;

require_once dirname(__FILE__) . '/../forms/module_instance_form.class.php';

class ModuleInstanceManagerCreatorComponent extends ModuleInstanceManager
{

    function run()
    {
        $trail = BreadcrumbTrail :: get_instance();
        $trail->add_help('module_instance general');
        
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $type = Request :: get(ModuleInstanceManager :: PARAM_TYPE);
        if ($type)
        {
            $module_instance = new ModuleInstance();
            $module_instance->set_type($type);
            $form = new ModuleInstanceForm(ModuleInstanceForm :: TYPE_CREATE, $module_instance, $this->get_url(array(
                    ModuleInstanceManager :: PARAM_TYPE => $type)));
            if ($form->validate())
            {
                $success = $form->create_module_instance();
                $this->redirect(Translation :: get($success ? 'ObjectAdded' : 'ObjectNotAdded', array(
                        'OBJECT' => Translation :: get('ModuleInstance')), Utilities :: COMMON_LIBRARIES), ($success ? false : true), array(
                        ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_BROWSE_INSTANCES));
            }
            else
            {
                $this->display_header();
                $form->display();
                $this->display_footer();
            }
        }
        else
        {
            $available_types = Module :: get_available_types();
            $table_data = array();
            foreach ($available_types as $available_type)
            {
                $name = htmlentities(Translation :: get('TypeName', null, $available_type));
                $row = array();
                $row[] = '<img src="' . Theme :: get_image_path($available_type) . '/logo/22.png" alt="' . $name . '" title="' . $name . '"/>';
                $row[] = $name;
                $row[] = htmlentities(Translation :: get('TypeDescription', null, $available_type));
                $row[] = Theme :: get_common_image('action_add', 'png', Translation :: get('AddModule'), $this->get_url(array(
                        self :: PARAM_TYPE => $available_type)), ToolbarItem :: DISPLAY_ICON);
                
                $table_data[] = $row;
            }
            $table = new SortableTableFromArray($table_data);
            $table->set_header(0, '');
            $table->set_header(1, 'Type');
            $table->set_header(2, 'Description');
            $table->set_header(3, '');
            
            $this->display_header();
            echo $table->as_html();
            $this->display_footer();
        }
    }

    function get_module_instance_types()
    {
        $active_managers = ModuleInstanceManager :: get_registered_types();
        
        $types = array();
        $sections = array();
        
        while ($active_manager = $active_managers->next_result())
        {
            $package_info = PackageInfo :: factory($active_manager->get_type(), $active_manager->get_name());
            $package_info = $package_info->get_package_info();
            
            $section = isset($package_info['package']['category']) ? $package_info['package']['category'] : 'various';
            $multiple = isset($package_info['package']['extra']['multiple']) ? $package_info['package']['extra']['multiple'] : false;
            
            $conditions = array();
            $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_TYPE, $active_manager->get_name());
            $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_INSTANCE_TYPE, $active_manager->get_type());
            $condition = new AndCondition($conditions);
            $count = $this->count_module_instances($condition);
            if (! $multiple && $count > 0)
            {
                continue;
            }
            
            if (! in_array($section, array_keys($sections)))
            {
                $manager_class = 'common\extensions\\' . $active_manager->get_type() . '\\' . Utilities :: underscores_to_camelcase($active_manager->get_type());
                $sections[$section] = Translation :: get('Category' . Utilities :: underscores_to_camelcase($section), null, $manager_class :: get_namespace());
            }
            
            if (! isset($types[$section]))
            {
                $types[$section] = array();
            }
            
            $types[$section][$active_manager->get_name()] = $active_manager;
            asort($types[$section]);
        }
        
        asort($sections);
        return array('sections' => $sections, 'types' => $types);
    }
}
?>