<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

use application\discovery\module\course\implementation\bamaflex\Material;

use application\discovery\module\course\implementation\bamaflex\Course;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\career\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\student_materials\Module
{

    /**
     * @return multitype:multitype:string
     */
    function get_table_data($enrollment)
    {
        $data = array();

        foreach ($this->get_courses() as $course)
        {
            if ($course->get_enrollment_id() == $enrollment->get_id())
            {
                $row = array();
                $row[] = $course->get_year();
                $row[] = $course->get_credits();

                if ($course->is_special_type())
                {
                    $course_type_image = '<img src="' . Theme :: get_image_path() . 'course_type/' . $course->get_type() . '.png" alt="' . Translation :: get($course->get_type_string()) . '" title="' . Translation :: get($course->get_type_string()) . '" />';
                    $row[] = $course_type_image;
                    LegendTable :: get_instance()->add_symbol($course_type_image, Translation :: get($course->get_type_string()), Translation :: get('CourseType'));
                }
                else
                {
                    $row[] = ' ';
                }

                $data_source = $this->get_module_instance()->get_setting('data_source');
                $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                        'data_source' => $data_source));

                if ($course_module_instance)
                {
                    $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters($course->get_programme_id(), 1);
                    $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
                }
                else
                {
                    $row[] = $course->get_name();
                }

                foreach ($this->get_mark_moments() as $mark_moment)
                {
                    $mark = $course->get_mark_by_moment_id($mark_moment->get_id());

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

                if ($course->has_children())
                {
                    foreach ($course->get_children() as $child)
                    {
                        $row = array();
                        $row[] = $child->get_year();
                        $row[] = $child->get_credits();

                        if ($child->is_special_type())
                        {
                            $child_type_image = '<img src="' . Theme :: get_image_path() . 'course_type/' . $child->get_type() . '.png" alt="' . Translation :: get($child->get_type_string()) . '" title="' . Translation :: get($child->get_type_string()) . '" />';
                            $row[] = $child_type_image;
                            LegendTable :: get_instance()->add_symbol($child_type_image, Translation :: get($child->get_type_string()), Translation :: get('CourseType'));
                        }
                        else
                        {
                            $row[] = ' ';
                        }

                        $row[] = '<span class="course_child">' . $child->get_name() . '</span>';

                        foreach ($this->get_mark_moments() as $mark_moment)
                        {
                            $mark = $child->get_mark_by_moment_id($mark_moment->get_id());

                            $row[] = $mark->get_result();
                            $row[] = null;
                        }

                        $data[] = $row;
                    }
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
        $headers[] = array(Translation :: get('Year'), 'class="code"');
        $headers[] = array(Translation :: get('Credits'), 'class="action"');
        $headers[] = array('', 'class="action"');
        $headers[] = array(Translation :: get('Course'));

        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = array($mark_moment->get_name());
            $headers[] = array();
        }

        return $headers;
    }

    function get_enrollment_courses($contract_type)
    {
        $html = array();
        $contracts = $this->get_contracts($contract_type);

        if (count($contracts) > 1)
        {
            $tabs = new DynamicTabsRenderer('contract_' . $contract_type . '_list');

            foreach ($contracts as $contract)
            {
                $last_enrollment = $contract[0];
                $contract_html = array();

                foreach ($contract as $enrollment)
                {
                    $table = new SortableTable($this->get_table_data($enrollment));

                    foreach ($this->get_table_headers() as $header_id => $header)
                    {
                        $table->set_header($header_id, $header[0], false);

                        if ($header[1])
                        {
                            $table->getHeader()->setColAttributes($header_id, $header[1]);
                        }
                    }

                    $contract_html[] = $table->toHTML();
                    $contract_html[] = '<br />';
                }

                $tab_name = array();

                if ($last_enrollment->is_special_result())
                {
                    $tab_image_path = Theme :: get_image_path(Utilities :: get_namespace_from_classname(Enrollment :: CLASS_NAME)) . 'result_type/' . $last_enrollment->get_result() . '.png';
                    $tab_image = '<img src="' . $tab_image_path . '" alt="' . Translation :: get($last_enrollment->get_result_string()) . '" title="' . Translation :: get($last_enrollment->get_result_string()) . '" />';
                    LegendTable :: get_instance()->add_symbol($tab_image, Translation :: get($last_enrollment->get_result_string()), Translation :: get('ResultType'));
                }
                else
                {
                    $tab_image = null;
                }

                $tab_name[] = $last_enrollment->get_training();
                if ($last_enrollment->get_unified_option())
                {
                    $tab_name[] = $last_enrollment->get_unified_option();
                }
                $tab_name = implode(' | ', $tab_name);

                $tabs->add_tab(new DynamicContentTab('contract_' . $last_enrollment->get_contract_id() . '', $tab_name, $tab_image_path, implode("\n", $contract_html)));
            }

            $html[] = $tabs->render();

        }
        else
        {
            $enrollments = $this->get_enrollments($contract_type);

            foreach ($enrollments as $enrollment)
            {
                $table_data = $this->get_table_data($enrollment);
                if (count($table_data) > 0)
                {
                    $html[] = '<table class="data_table" id="tablename"><thead><tr><th class="action">';

                    if ($enrollment->is_special_result())
                    {
                        $tab_image_path = Theme :: get_image_path(Utilities :: get_namespace_from_classname(Enrollment :: CLASS_NAME)) . 'result_type/' . $enrollment->get_result() . '.png';
                        $tab_image = '<img src="' . $tab_image_path . '" alt="' . Translation :: get($enrollment->get_result_string()) . '" title="' . Translation :: get($enrollment->get_result_string()) . '" />';
                        $html[] = $tab_image;
                        LegendTable :: get_instance()->add_symbol($tab_image, Translation :: get($enrollment->get_result_string()), Translation :: get('ResultType'));
                    }
                    else
                    {
                        $tab_image = null;
                    }

                    $html[] = '</th><th>';

                    $enrollment_name = array();

                    $enrollment_name[] = $enrollment->get_year();
                    $enrollment_name[] = $enrollment->get_training();

                    if ($enrollment->get_unified_option())
                    {
                        $enrollment_name[] = $enrollment->get_unified_option();
                    }

                    if ($enrollment->get_unified_trajectory())
                    {
                        $enrollment_name[] = $enrollment->get_unified_trajectory();
                    }

                    $html[] = implode(' | ', $enrollment_name);
                    $html[] = '</th></tr></thead></table>';
                    $html[] = '<br />';

                    $table = new SortableTable($this->get_table_data($enrollment));

                    foreach ($this->get_table_headers() as $header_id => $header)
                    {
                        $table->set_header($header_id, $header[0], false);

                        if ($header[1])
                        {
                            $table->getHeader()->setColAttributes($header_id, $header[1]);
                        }
                    }

                    $html[] = $table->toHTML();
                    $html[] = '<br />';
                }
            }
        }

        return implode("\n", $html);
    }

    function get_enrollment_materials_by_type($enrollment_id, $type)
    {
        $table_data = array();
        $total_price = 0;

        $courses = DataManager :: get_instance($this->get_module_instance())->retrieve_courses($enrollment_id);

        foreach ($courses as $course)
        {
            $materials = DataManager :: get_instance($this->get_module_instance())->retrieve_materials($course->get_programme_id(), $type);

            foreach ($materials as $material)
            {
                $table_row = array();

                $data_source = $this->get_module_instance()->get_setting('data_source');
                $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                        'data_source' => $data_source));

                if ($course_module_instance)
                {
                    $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters($course->get_programme_id(), 1);
                    $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                    $table_row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
                }
                else
                {
                    $table_row[] = $course->get_name();
                }

                $table_row[] = $material->get_group();
                $table_row[] = $material->get_title();
                $table_row[] = $material->get_edition();
                $table_row[] = $material->get_author();
                $table_row[] = $material->get_editor();
                $table_row[] = $material->get_isbn();
                $table_row[] = $material->get_medium();
                $table_row[] = $material->get_description();
                if ($material->get_price())
                {
                    $table_row[] = $material->get_price_string();
                }
                else
                {
                    $table_row[] = '';
                }

                if ($material->get_for_sale())
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/for_sale.png" alt="' . Translation :: get('IsForSale') . '" title="' . Translation :: get('IsForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsForSale'), Translation :: get('ForSale'));
                    $table_row[] = $image;
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'material/not_for_sale.png" alt="' . Translation :: get('IsNotForSale') . '" title="' . Translation :: get('IsNotForSale') . '"/>';
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get('IsNotForSale'), Translation :: get('ForSale'));
                    $table_row[] = $image;
                }

                $total_price += $material->get_price();

                $table_data[] = $table_row;
            }
        }

        if (count($table_data) > 0)
        {
            $table = new SortableTable($table_data);
            $table->set_header(0, Translation :: get('Course'), false);
            $table->set_header(1, Translation :: get('Group'), false);
            $table->set_header(2, Translation :: get('Title'), false);
            $table->set_header(3, Translation :: get('Edition'), false);
            $table->set_header(4, Translation :: get('Author'), false);
            $table->set_header(5, Translation :: get('Editor'), false);
            $table->set_header(6, Translation :: get('Isbn'), false);
            $table->set_header(7, Translation :: get('Medium'), false);
            $table->set_header(8, Translation :: get('Remarks'), false);
            $table->set_header(9, Translation :: get('Price'), false);
            $table->set_header(10, '', false);

            if($total_price)
            {
                $total_price .= ' &euro;';
            }

            $html[] = $table->as_html($total_price, 9);
        }

        return implode("\n", $html);
    }

    function get_enrollment_materials($enrollment_id)
    {
        $tabs = new DynamicTabsRenderer('study_materials_' . $enrollment_id);
        $tabs->add_tab(new DynamicContentTab(Material :: TYPE_REQUIRED, Translation :: get('Required'), null, $this->get_enrollment_materials_by_type($enrollment_id, Material :: TYPE_REQUIRED)));
        $tabs->add_tab(new DynamicContentTab(Material :: TYPE_OPTIONAL, Translation :: get('Optional'), null, ''));
        return $tabs->render();
    }

    function get_enrollments($year)
    {
        $year_enrollments = array();

        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_application()->get_user_id());

        foreach ($enrollments as $enrollment)
        {
            if ($enrollment->get_year() == $year)
            {
                $year_enrollments[] = $enrollment;
            }
        }

        $tabs = new DynamicTabsRenderer('year_enrollment_' . $year);

        foreach ($year_enrollments as $year_enrollment)
        {
            $tab_name = array();

            if ($year_enrollment->is_special_result())
            {
                $tab_image_path = Theme :: get_image_path(Utilities :: get_namespace_from_classname(Enrollment :: CLASS_NAME)) . 'result_type/' . $year_enrollment->get_result() . '.png';
                $tab_image = '<img src="' . $tab_image_path . '" alt="' . Translation :: get($year_enrollment->get_result_string()) . '" title="' . Translation :: get($year_enrollment->get_result_string()) . '" />';
                LegendTable :: get_instance()->add_symbol($tab_image, Translation :: get($year_enrollment->get_result_string()), Translation :: get('ResultType'));
            }
            else
            {
                $tab_image_path = null;
            }

            $tab_name[] = $year_enrollment->get_training();
            if ($year_enrollment->get_unified_option())
            {
                $tab_name[] = $year_enrollment->get_unified_option();
            }
            $tab_name = implode(' | ', $tab_name);

            $tabs->add_tab(new DynamicContentTab($year_enrollment->get_id(), $tab_name, $tab_image_path, $this->get_enrollment_materials($year_enrollment->get_id())));
        }

        return $tabs->render();
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\career.Module::render()
     */
    function render()
    {
        $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_application()->get_user_id());
        $tabs = new DynamicTabsRenderer('year_list');

        foreach ($years as $year)
        {
            $tabs->add_tab(new DynamicContentTab($year, $year, null, $this->get_enrollments($year)));
        }

        return $tabs->render();
    }
}
?>