<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;

use application\discovery\SortableTable;

use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\enrollment\Module
{

    function get_enrollments_table($contract_type = Enrollment :: CONTRACT_TYPE_ALL)
    {
        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $enrollments = $this->get_enrollments();
        }
        else
        {
            $enrollments = array();
            foreach ($this->get_enrollments() as $enrollment)
            {
                if ($enrollment->get_contract_type() == $contract_type)
                {
                    $enrollments[] = $enrollment;
                }
            }
        }

        $data = array();

        foreach ($enrollments as $key => $enrollment)
        {
            $row = array();
            $row[] = $enrollment->get_year();
            $row[] = $enrollment->get_faculty();
            $row[] = $enrollment->get_training();
            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();
            if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
            {
                $row[] = Translation :: get($enrollment->get_contract_type_string());
            }

            $class = 'enrollment" style="" id="enrollment_' . $key;
            $details_action = new ToolbarItem(Translation :: get('ShowCourses'), Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            $row[] = $details_action->as_html();
            $data[] = $row;
        }

        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false);
        $table->set_header(1, Translation :: get('Faculty'), false);
        $table->set_header(2, Translation :: get('Training'), false);
        $table->set_header(3, Translation :: get('Option'), false);
        $table->set_header(4, Translation :: get('Trajectory'), false);
        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $table->set_header(5, Translation :: get('Contract'), false);
            $table->set_header(6, '', false);
        }
        else
        {
            $table->set_header(5, '', false);
        }

        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\enrollment.Module::render()
     */
    function render()
    {
        $html = array();

        $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types($this->get_application()->get_user_id());

        $tabs = new DynamicTabsRenderer('enrollment_list');
        $tabs->add_tab(new DynamicContentTab(Enrollment :: CONTRACT_TYPE_ALL, Translation :: get('AllContracts'), Theme :: get_image_path() . 'contract_type/0.png', $this->get_enrollments_table(Enrollment :: CONTRACT_TYPE_ALL)->toHTML()));

        foreach ($contract_types as $contract_type)
        {
            $tabs->add_tab(new DynamicContentTab($contract_type, Translation :: get(Enrollment :: get_contract_type_string_static($contract_type)), Theme :: get_image_path() . 'contract_type/' . $contract_type . '.png', $this->get_enrollments_table($contract_type)->toHTML()));
        }

        $html[] = $tabs->render();

//        $path = Path :: namespace_to_full_path('application\discovery\module\enrollment', true) . 'resources/javascript/enrollment.js';
//        $html[] = ResourceManager :: get_instance()->get_resource_html($path);

        return implode("\n", $html);
    }
}
?>