<?php
namespace application\discovery\module\course_results;

use common\libraries\Request;

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
	const PARAM_PROGRAMME_ID = 'programme_id';
    /**
     * @var multitype:\application\discovery\module\course_results\Course
     */
    private $course_results;

    /**
     * @var multitype:\application\discovery\module\course_results\MarkMoment
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
        $this->course_results = $this->get_data_manager()->retrieve_course_results(Request:: get(self :: PARAM_PROGRAMME_ID));
        $this->mark_moments = $this->get_data_manager()->retrieve_mark_moments(Request :: get(self :: PARAM_PROGRAMME_ID));
    }

    /**
     * @return multitype:\application\discovery\module\course_results\Course
     */
    function get_course_results()
    {
        return $this->course_results;
    }

    /**
     * @return multitype:\application\discovery\module\course_results\MarkMoment
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

        return implode("\n", $html);
    }
}
?>