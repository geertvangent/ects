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
            $row[] = $enrollment->get_training();

            $class = 'enrollment" style="" id="enrollment_' . $key;
            $details_action = new ToolbarItem(Translation :: get('ShowCourses'), Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            $row[] = $details_action->as_html();
            $data[] = $row;
        }

        //        $path = Path :: namespace_to_full_path(__NAMESPACE__, true) . 'resources/javascript/enrollment.js';
        //        $html[] = ResourceManager :: get_instance()->get_resource_html($path);


        return implode("\n", $html);
    }
}
?>