<?php
namespace application\discovery;

use common\libraries\ToolbarItem;
use common\libraries\SortableTableFromArray;
use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\BreadcrumbTrail;
use common\libraries\Utilities;
use common\libraries\Theme;

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
            $form = new ModuleInstanceForm(ModuleInstanceForm :: TYPE_CREATE, $module_instance,
                    $this->get_url(array(ModuleInstanceManager :: PARAM_TYPE => $type)));
            if ($form->validate())
            {
                $success = $form->create_module_instance();
                $this->redirect(
                        Translation :: get($success ? 'ObjectAdded' : 'ObjectNotAdded',
                                array('OBJECT' => Translation :: get('ModuleInstance')), Utilities :: COMMON_LIBRARIES),
                        ($success ? false : true),
                        array(
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
                $row[] = Theme :: get_common_image('action_add', 'png', Translation :: get('AddModuleInstance'),
                        $this->get_url(array(self :: PARAM_TYPE => $available_type)), ToolbarItem :: DISPLAY_ICON);

                $table_data[] = $row;
            }
            $table = new SortableTableFromArray($table_data);
            $parameters = $this->get_parameters();
            $table->set_additional_parameters($parameters);
            $table->set_header(0, '');
            $table->set_header(1, 'Type');
            $table->set_header(2, 'Description');
            $table->set_header(3, '');

            $this->display_header();
            echo $table->as_html();
            $this->display_footer();
        }
    }
}
