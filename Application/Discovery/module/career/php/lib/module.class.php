<?php
namespace application\discovery\module\career;

use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\DiscoveryModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    /**
     * @var multitype:\application\discovery\module\career\Course
     */
    private $courses;

    function __construct(Application $application, DiscoveryModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->courses = DataManager :: get_instance($module_instance)->retrieve_root_courses($application->get_user_id());

    }

    /**
     * @return multitype:\application\discovery\module\career\Course
     */
    function get_courses()
    {
        return $this->courses;
    }

    /**
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();

        foreach ($this->courses as $career)
        {
            $row = array();
            $row[] = $career->get_year();
            $row[] = $career->get_name();
            $data[] = $row;
        }

        return $data;
    }

    /**
     * @return multitype:string
     */
    function get_table_headers()
    {
        $headers = array();
        $headers[] = Translation :: get('Year');
        $headers[] = Translation :: get('Course');
        return $headers;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/career.png);">';
        $html[] = '<div class="title">';
        $html[] = Translation :: get('Careers');
        $html[] = '</div>';

        $html[] = '<div class="description">';

        $table = new SortableTableFromArray($this->get_table_data());

        foreach ($this->get_table_headers() as $header_id => $header_name)
        {
            $table->set_header($header_id, $header_name);
        }

        $html[] = $table->toHTML();

        $html[] = '</div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }
}
?>