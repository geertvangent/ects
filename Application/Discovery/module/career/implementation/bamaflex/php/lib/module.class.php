<?php
namespace application\discovery\module\career\implementation\bamaflex;

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

class Module extends \application\discovery\module\career\Module
{
    /**
     * @var boolean
     */
    private $show_course_type_legend = false;

    /**
     * @return string
     */
    function get_course_type_legend()
    {
        $data = array();

        foreach(Course :: get_types() as $type)
        {
            $name = Translation :: get(Course :: type_string($type));

            $row = array();
            $row[] = '<img src="' . Theme :: get_image_path() . 'course_type/' . $type . '.png" alt="'. $name .'" title="'. $name .'" >';
            $row[] = Translation :: get(Course :: type_string($type));
            $data[] = $row;
        }

        $legend = new SortableTable($data);

        $legend->set_header(0, '', false);
        $legend->set_header(1, Translation :: get('CourseType'), false);
        $legend->getHeader()->setColAttributes(0, 'class="action"');

        return $legend->toHTML();
    }

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
                $row[] = '<img src="' . Theme :: get_image_path() . 'course_type/' . $course->get_type() . '.png" alt="'. $course->get_type_string() .'" title="'. $course->get_type_string() .'" >';
                $row[] = $course->get_name();

                foreach ($this->get_mark_moments() as $mark_moment)
                {
                    $mark = $course->get_mark_by_moment_id($mark_moment->get_id());
                    $row[] = $mark->get_visual_result();
                    $row[] = $mark->get_status();
                }

                $data[] = $row;

                if ($course->has_children())
                {
                    foreach ($course->get_children() as $child)
                    {
                        $row = array();
                        $row[] = $child->get_year();
                        $row[] = $child->get_credits();
                        $row[] = '<img src="' . Theme :: get_image_path() . 'course_type/' . $course->get_type() . '.png" >';
                        $row[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-style: italic;">' . $child->get_name() . '</span>';

                        foreach ($this->get_mark_moments() as $mark_moment)
                        {
                            $row[] = $child->get_mark_by_moment_id($mark_moment->get_id())->get_result();
                            $row[] = $child->get_mark_by_moment_id($mark_moment->get_id())->get_status();
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

    function get_enrollments($contract_type)
    {
        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_application()->get_user_id());

        $contract_type_enrollments = array();

        foreach ($enrollments as $enrollment)
        {
            if ($enrollment->get_contract_type() == $contract_type)
            {
                $contract_type_enrollments[] = $enrollment;
            }
        }

        return $contract_type_enrollments;
    }

    function get_contracts($contract_type)
    {
        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_application()->get_user_id());

        $contract_enrollments = array();

        foreach ($enrollments as $enrollment)
        {
            if ($enrollment->get_contract_type() == $contract_type)
            {
                $contract_enrollments[$enrollment->get_contract_id()][] = $enrollment;
            }
        }

        return $contract_enrollments;
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
                    if (count($contract) > 1)
                    {
                        $contract_html[] = '<table class="data_table" id="tablename"><thead><tr><th>';
                        $contract_html[] = $enrollment;
                        $contract_html[] = '</th></tr></thead></table>';
                        $contract_html[] = '<br />';
                    }

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
                $tab_name[] = $last_enrollment->get_training();
                if ($last_enrollment->get_unified_option())
                {
                    $tab_name[] = $last_enrollment->get_unified_option();
                }
                $tab_name = implode(' | ', $tab_name);

                $tabs->add_tab(new DynamicContentTab('contract_' . $last_enrollment->get_contract_id() . '', $tab_name, Theme :: get_image_path() . 'contract_type/' . $contract_type . '.png', implode("\n", $contract_html)));
            }

            $html[] = $tabs->render();

        }
        else
        {
            $enrollments = $this->get_enrollments($contract_type);

            foreach ($enrollments as $enrollment)
            {
                $html[] = '<h4>' . $enrollment . '</h4>';

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

            }
        }

        return implode("\n", $html);
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\career.Module::render()
     */
    function render()
    {
        $html = array();

        $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types($this->get_application()->get_user_id());

        $tabs = new DynamicTabsRenderer('enrollment_list');

        foreach ($contract_types as $contract_type)
        {
            $tabs->add_tab(new DynamicContentTab($contract_type, Translation :: get(Enrollment :: contract_type_string($contract_type)), Theme :: get_image_path() . 'contract_type/' . $contract_type . '.png', $this->get_enrollment_courses($contract_type)));
        }

        $html[] = $tabs->render();

        $html[] = $this->get_course_type_legend();

        return implode("\n", $html);
    }
}
?>