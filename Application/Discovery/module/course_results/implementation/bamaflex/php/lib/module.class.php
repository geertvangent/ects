<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\course_results\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\course_results\Module
{

    /**
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();
        
        foreach ($this->get_course_results() as $course_result)
        {
            $row = array();
            $row[] = $course_result->get_person_last_name();
            $row[] = $course_result->get_person_first_name();
            $row[] = $course_result->get_trajectory_type_string();
            
            foreach ($this->get_mark_moments() as $mark_moment)
            {
                $mark = $course_result->get_mark_by_moment_id($mark_moment->get_id());
                
                if ($mark->get_result())
                {
                    $row[] = $mark->get_visual_result();
                }
                else
                {
                    $row[] = $mark->get_sub_status();
                }
                
                if ($mark->get_status())
                {
                    $mark_status_image = '<img src="' . Theme :: get_image_path() . 'status_type/' . $mark->get_status() . '.png" alt="' . Translation :: get($mark->get_status_string()) . '" title="' . Translation :: get($mark->get_status_string()) . '" />';
                    $row[] = $mark_status_image;
                    LegendTable :: get_instance()->add_symbol($mark_status_image, Translation :: get($mark->get_status_string()), Translation :: get('MarkStatus'));
                }
                else
                {
                    $row[] = null;
                }
            }
            
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
        $headers[] = array(Translation :: get('PersonLastName'));
        $headers[] = array(Translation :: get('PersonFirstName'));
        $headers[] = array(Translation :: get('TrajectoryType'));
        
        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = array($mark_moment->get_name());
            $headers[] = array();
        }
        
        return $headers;
    }

    function get_course_results_table()
    {
        $html = array();
        
        $table_data = $this->get_table_data();
        if (count($table_data) > 0)
        {           
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
        }
        
        return implode("\n", $html);
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\course_results.Module::render()
     */
    function render()
    {
        $html = array();
        $html[] = $this->get_course_results_table();
        
        return implode("\n", $html);
    }
}
?>