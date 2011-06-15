<?php
namespace application\discovery\module\career\implementation\bamaflex;

use common\libraries\Theme;

use common\libraries\SortableTableFromArray;

use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\module\career\DataManager;

class Module extends \application\discovery\module\career\Module
{

    /**
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();

        foreach ($this->get_courses() as $course)
        {
            $row = array();
            $row[] = $course->get_year();
            $row[] = $course->get_trajectory_part();
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
                    $row[] = $child->get_trajectory_part();
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
    function get_table_headers()
    {
        $headers = array();
        $headers[] = Translation :: get('Year');
        $headers[] = Translation :: get('TrajectoryPart');
        $headers[] = Translation :: get('Credits');
        $headers[] = Translation :: get('Course');
        //$headers[] = Translation :: get('Weight');


        return $headers;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\career.Module::render()
     */
    function render()
    {
        $html = array();
        $html[] = parent :: render();
        //
        //        if (count($this->get_career()->get_address()) > 1)
        //        {
        //            $data = array();
        //
        //            foreach ($this->get_career()->get_address() as $address)
        //            {
        //                $row = array();
        //                $row[] = Translation :: get($address->get_type_string());
        //                $row[] = $address->get_street();
        //                $row[] = $address->get_number();
        //                $row[] = $address->get_box();
        //                $row[] = $address->get_room();
        //                $row[] = $address->get_unified_city();
        //                $row[] = $address->get_unified_city_zip_code();
        //                $row[] = $address->get_region();
        //                $row[] = $address->get_country();
        //                $data[] = $row;
        //            }
        //
        //            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'types/address.png);">';
        //            $html[] = '<div class="title">';
        //            $html[] = Translation :: get('Addresses');
        //            $html[] = '</div>';
        //
        //            $html[] = '<div class="description">';
        //
        //            $table = new SortableTableFromArray($data);
        //            $table->set_header(0, Translation :: get('Type'));
        //            $table->set_header(1, Translation :: get('Street'));
        //            $table->set_header(2, Translation :: get('Number'));
        //            $table->set_header(3, Translation :: get('Box'));
        //            $table->set_header(4, Translation :: get('Room'));
        //            $table->set_header(5, Translation :: get('City'));
        //            $table->set_header(6, Translation :: get('ZipCode'));
        //            $table->set_header(7, Translation :: get('Region'));
        //            $table->set_header(8, Translation :: get('Country'));
        //            $html[] = $table->toHTML();
        //
        //            $html[] = '</div>';
        //            $html[] = '</div>';
        //        }


        return implode("\n", $html);
    }
}
?>