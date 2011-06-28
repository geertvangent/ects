<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use common\libraries\ResourceManager;

use common\libraries\Path;

use common\libraries\ToolbarItem;

use application\discovery\SortableTable;

use common\libraries\Theme;

use common\libraries\SortableTableFromArray;

use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\enrollment\Module
{

    /**
     * @param multitype:Course $courses
     */
    function process_enrollment_course_data($courses)
    {
        $data = array();

        foreach ($courses as $course)
        {
            $row = array();
            $row[] = $course->get_year();
            //$row[] = $course->get_trajectory_part();
            $row[] = $course->get_credits();
            $row[] = $course->get_name();
            //$row[] = $course->get_weight();


            $data[] = $row;

            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $row = array();
                    $row[] = $child->get_year();
                    //$row[] = $child->get_trajectory_part();
                    $row[] = $child->get_credits();
                    $row[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-style: italic;">' . $child->get_name() . '</span>';
                    //$row[] = $child->get_weight();


                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    /**
     * @return multitype:string
     */
    function get_enrollment_course_table_headers()
    {
        $headers = array();
        $headers[] = array(Translation :: get('Year'), 'class="code"');
        //$headers[] = array(Translation :: get('TrajectoryPart'), 'class="action"');
        $headers[] = array(Translation :: get('Credits'), 'class="action"');
        $headers[] = array(Translation :: get('Course'));
        //$headers[] = array(Translation :: get('Weight'));


        return $headers;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\enrollment.Module::render()
     */
    function render()
    {
        $html = array();

        $data = array();

        foreach ($this->get_enrollments() as $key => $enrollment)
        {
            $row = array();
            $row[] = $enrollment->get_year();
            $row[] = $enrollment->get_faculty();
            $row[] = $enrollment->get_training();
            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();
            $row[] = $enrollment->get_contract_type_string();

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
        $table->set_header(5, Translation :: get('Contract'), false);
        $table->set_header(6, '', false);
        $html[] = $table->toHTML();

        foreach ($this->get_enrollments() as $key => $enrollment)
        {
            $courses = DataManager :: get_instance($this->get_module_instance())->retrieve_courses($enrollment, $this->get_application()->get_user_id());

            $html[] = '<div class="enrollment_courses" id="enrollment_' . $key . '_courses" style="display: none;">';
            $html[] = '<h4>';
            $html[] = $enrollment;
            $html[] = '</h4>';

            $table = new SortableTable($this->process_enrollment_course_data($courses));

            foreach ($this->get_enrollment_course_table_headers() as $header_id => $header)
            {
                $table->set_header($header_id, $header[0], false);

                if ($header[1])
                {
                    $table->getHeader()->setColAttributes($header_id, $header[1]);
                }
            }

            $html[] = $table->toHTML();

            $html[] = '</div>';
        }

        $path = Path :: namespace_to_full_path('application\discovery\module\enrollment', true) . 'resources/javascript/enrollment.js';
        $html[] = ResourceManager :: get_instance()->get_resource_html($path);

        return implode("\n", $html);
    }
}
?>