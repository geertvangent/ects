<?php
namespace application\discovery\module\career\implementation\bamaflex;

use user\UserDataManager;

use common\libraries\Display;

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

class Module extends \application\discovery\module\career\Module
{
    private $result_right;

    /**
     * @return multitype:multitype:string
     */
    function get_table_data($enrollment)
    {
        $data = array();
        $training = $enrollment->get_training_object();

        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                'data_source' => $data_source));

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

                if ($course_module_instance)
                {
                    $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters($course->get_programme_id(), $course->get_source());
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

                    if ($mark->get_publish_status() == 1 || ! $training->is_current() || $this->result_right)
                    {
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
                    else
                    {
                        if ($mark->get_result())
                        {
                            $result_na_image = '<img src="' . Theme :: get_image_path() . 'mark_result_na.png" alt="' . Translation :: get('ResultNotYetAvailable') . '" title="' . Translation :: get('ResultNotYetAvailable') . '" />';
                            $row[] = $result_na_image;
                            LegendTable :: get_instance()->add_symbol($result_na_image, Translation :: get('ResultNotYetAvailable'), Translation :: get('MarkResult'));
                        }
                        else
                        {
                            $row[] = null;
                        }
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

                        if ($course_module_instance)
                        {
                            $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters($child->get_programme_id(), $child->get_source());
                            $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                            $row[] = '<span class="course_child"><a href="' . $url . '">' . $child->get_name() . '</a></span>';
                        }
                        else
                        {
                            $row[] = '<span class="course_child">' . $child->get_name() . '</span>';
                        }

                        foreach ($this->get_mark_moments() as $mark_moment)
                        {

                            $mark = $child->get_mark_by_moment_id($mark_moment->get_id());
                            if ($mark->get_publish_status() == 1 || ! $training->is_current() || $this->result_right)
                            {
                                $row[] = $mark->get_result();
                                $row[] = null;
                            }
                            else
                            {
                                if ($mark->get_result())
                                {
                                    $result_na_image = '<img src="' . Theme :: get_image_path() . 'mark_result_na.png" alt="' . Translation :: get('ResultNotYetAvailable') . '" title="' . Translation :: get('ResultNotYetAvailable') . '" />';
                                    $row[] = $result_na_image;
                                    LegendTable :: get_instance()->add_symbol($result_na_image, Translation :: get('ResultNotYetAvailable'), Translation :: get('MarkResult'));
                                }
                                else
                                {
                                    $row[] = null;
                                }
                                $row[] = null;
                            }
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
        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_career_parameters());

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
        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_career_parameters());

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
                    $tab_image_path = null;
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

    /* (non-PHPdoc)
     * @see application\discovery\module\career.Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();

        if (! Rights :: get_instance()->user_module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_career_parameters()))
        {
            Display :: not_allowed();
        }

        $this->result_right = Rights :: get_instance()->module_is_allowed(Rights :: RESULT_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_career_parameters());

        $html = array();

        if ($this->get_career_parameters()->get_user_id() != $this->get_application()->get_user_id())
        {
            $user = UserDataManager :: get_instance()->retrieve_user($this->get_career_parameters()->get_user_id());
            $html[] = '<h3>' . $user->get_fullname() . '</h3>';
        }

        if (count($this->get_courses()) > 0)
        {
            $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types($this->get_career_parameters());

            $tabs = new DynamicTabsRenderer('enrollment_list');

            foreach ($contract_types as $contract_type)
            {
                $tabs->add_tab(new DynamicContentTab($contract_type, Translation :: get(Enrollment :: contract_type_string($contract_type)), Theme :: get_image_path() . 'contract_type/' . $contract_type . '.png', $this->get_enrollment_courses($contract_type)));
            }

            $html[] = $tabs->render();
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
}
?>