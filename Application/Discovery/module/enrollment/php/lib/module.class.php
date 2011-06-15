<?php
namespace application\discovery\module\enrollment;

use common\libraries\Path;

use common\libraries\WebApplication;

use common\libraries\ResourceManager;

use common\libraries\ToolbarItem;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\SortableTable;
use application\discovery\DiscoveryModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    /**
     * @var multitype:\application\discovery\module\enrollment\Enrollment
     */
    private $enrollments;

    function __construct(Application $application, DiscoveryModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->enrollments = DataManager :: get_instance($module_instance)->retrieve_enrollments($application->get_user_id());

    }

    /**
     * @return multitype:\application\discovery\module\enrollment\Enrollment
     */
    function get_enrollments()
    {
        return $this->enrollments;
    }

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
            $row[] = $course->get_name();
            $data[] = $row;

            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $row = array();
                    $row[] = $child->get_year();
                    $row[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-style: italic;">' . $child->get_name() . '</span>';
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
        $headers[] = array(Translation :: get('Year'));
        $headers[] = array(Translation :: get('Course'));
        return $headers;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();

        $data = array();

        foreach ($this->enrollments as $key => $enrollment)
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

        foreach ($this->enrollments as $key => $enrollment)
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

                if($header[1])
                {
                    $table->getHeader()->setColAttributes($header_id, $header[1]);
                }
            }

            $html[] = $table->toHTML();

            $html[] = '</div>';
        }

        $path = Path :: namespace_to_full_path(__NAMESPACE__, true) . 'resources/javascript/enrollment.js';
        $html[] = ResourceManager :: get_instance()->get_resource_html($path);

        return implode("\n", $html);
    }
}
?>