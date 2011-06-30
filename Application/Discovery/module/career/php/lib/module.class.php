<?php
namespace application\discovery\module\career;

use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
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
     * @var multitype:\application\discovery\module\career\Course
     */
    private $courses;

    /**
     * @var multitype:\application\discovery\module\career\MarkMoment
     */
    private $mark_moments;

    function __construct(Application $application, DiscoveryModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->retrieve_data();
    }

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function retrieve_data()
    {
        $this->courses = $this->get_data_manager()->retrieve_courses($this->get_application()->get_user_id());
        $this->mark_moments = $this->get_data_manager()->retrieve_mark_moments($this->get_application()->get_user_id());

//        $marks = $this->get_data_manager()->retrieve_marks($this->get_application()->get_user_id());
//        dump($marks);
    }

    /**
     * @return multitype:\application\discovery\module\career\Course
     */
    function get_courses()
    {
        return $this->courses;
    }

    /**
     * @return multitype:\application\discovery\module\career\MarkMoment
     */
    function get_mark_moments()
    {
        return $this->mark_moments;
    }

    /**
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();

        foreach ($this->courses as $course)
        {
            $row = array();
            $row[] = $course->get_year();
            $row[] = $course->get_name();
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
        $headers[] = array(Translation :: get('Year'), 'class="code"');
        $headers[] = array(Translation :: get('Course'));

        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = array($mark_moment->get_name());
        }

        return $headers;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        //        $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/career.png);">';
        //        $html[] = '<div class="title">';
        //        $html[] = Translation :: get('Careers');
        //        $html[] = '</div>';
        //
        //        $html[] = '<div class="description">';


        $table = new SortableTable($this->get_table_data());

        foreach ($this->get_table_headers() as $header_id => $header)
        {
            $table->set_header($header_id, $header[0], false);

            if ($header[1])
            {
                $table->getHeader()->setColAttributes($header_id, $header[1]);
            }
        }

        $html[] = $table->toHTML();

        //        $html[] = '</div>';
        //        $html[] = '</div>';


        return implode("\n", $html);
    }
}
?>