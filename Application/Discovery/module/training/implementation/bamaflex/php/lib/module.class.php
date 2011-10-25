<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use application\discovery\SortableTable;

use common\libraries\Translation;

class Module extends \application\discovery\module\faculty\Module
{

    function get_faculties_table($year = 0)
    {
        $faculties = $this->get_faculties_data($year);
        
        $data = array();
        
        foreach ($faculties as $key => $faculty)
        {
            $row = array();
            if (! $year)
            {
                $row[] = $faculty->get_year();
            }
            //            $data_source = $this->get_module_instance()->get_setting('data_source');
            //            $course_result_module_instance = \application\discovery\Module :: exists('application\discovery\module\course_results\implementation\bamaflex', array(
            //                    'data_source' => $data_source));
            //
            //            if ($course_result_module_instance)
            //            {
            //                $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters($faculty->get_programme_id(), 1);
            //                $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
            //                $row[] = '<a href="' . $url . '">' . $faculty->get_name() . '</a>';
            //            }
            //            else
            //            {
            $row[] = $faculty->get_name();
            $row[] = $faculty->get_deans_string();
            //            }
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        if (! $year)
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('Dean'), false);
        }
        else
        {
            $table->set_header(0, Translation :: get('Name'), false);
            $table->set_header(1, Translation :: get('Dean'), false);
        }
        return $table;
    }
}
?>