<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use common\libraries\Display;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\teaching_assignment\Module
{
    private $cache_teaching_assignments = array();

    function get_teaching_assignments_data($year = 0, $type = TeachingAssignment::TYPE_TEACHER)
    {
        if (! isset($this->cache_teaching_assignments[$year][$type]))
        {
            if ($year == 0)
            {
                $teaching_assignments = array();
                foreach ($this->get_teaching_assignments() as $teaching_assignment)
                {
                    if ($teaching_assignment->get_type() == $type)
                    {
                        $teaching_assignments[] = $teaching_assignment;
                    }
                }
            }
            else
            {
                $teaching_assignments = array();
                foreach ($this->get_teaching_assignments() as $teaching_assignment)
                {
                    if ($teaching_assignment->get_year() == $year && $teaching_assignment->get_type() == $type)
                    {
                        $teaching_assignments[] = $teaching_assignment;
                    }
                }
            }
            $this->cache_teaching_assignments[$year][$type] = $teaching_assignments;
        }
        return $this->cache_teaching_assignments[$year][$type];
    }

    function has_teaching_assignments($year, $type)
    {
        return count($this->get_teaching_assignments_data($year, $type)) > 0;
    }

    function get_teaching_assignments_table($year = 0, $type = TeachingAssignment::TYPE_TEACHER)
    {
        $teaching_assignments = $this->get_teaching_assignments_data($year, $type);

        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                'data_source' => $data_source));

        $course_result_module_instance = \application\discovery\Module :: exists('application\discovery\module\course_results\implementation\bamaflex', array(
                'data_source' => $data_source));

        $training_module_instance = \application\discovery\Module :: exists('application\discovery\module\training\implementation\bamaflex', array(
                'data_source' => $data_source));

        $training_info_module_instance = \application\discovery\Module :: exists('application\discovery\module\training_info\implementation\bamaflex', array(
                'data_source' => $data_source));

        foreach ($teaching_assignments as $key => $teaching_assignment)
        {
            $row = array();
            if (! $year)
            {
                $row[] = $teaching_assignment->get_year();
            }

            if ($training_module_instance)
            {
                $parameters = new \application\discovery\module\training\implementation\bamaflex\Parameters($teaching_assignment->get_faculty_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($training_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_faculty() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_faculty();
            }

            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($teaching_assignment->get_training_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_training() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_training();
            }

            if ($course_module_instance)
            {
                $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters($teaching_assignment->get_programme_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_name() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_name();
            }
            $row[] = $teaching_assignment->get_credits();
            $image = '<img src="' . Theme :: get_image_path() . 'timeframe/' . $teaching_assignment->get_timeframe_id() . '.png" alt="' . Translation :: get($teaching_assignment->get_timeframe()) . '" title="' . Translation :: get($teaching_assignment->get_timeframe()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol($image, Translation :: get($teaching_assignment->get_timeframe()), Translation :: get('Timeframe'));

            if ($course_result_module_instance)
            {
                $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters($teaching_assignment->get_programme_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                $row[] = Theme :: get_common_image('action_details', 'png', Translation :: get('CourseResults'), $url, ToolbarItem :: DISPLAY_ICON);
            }

            $data[] = $row;
        }

        $table = new SortableTable($data);
        if (! $year)
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Faculty'), false);
            $table->set_header(2, Translation :: get('Training'), false);
            $table->set_header(3, Translation :: get('Name'), false);
            $table->set_header(4, Translation :: get('Credits'), false, 'class="action"');

            $table->set_header(5, '<img src="' . Theme :: get_image_path() . 'timeframe.png"/>', false);
            $table->set_header(6, '', false);
        }
        else
        {
            $table->set_header(0, Translation :: get('Faculty'), false);
            $table->set_header(1, Translation :: get('Training'), false);
            $table->set_header(2, Translation :: get('Name'), false);
            $table->set_header(3, Translation :: get('Credits'), false, 'class="action"');
            $table->set_header(4, '<img src="' . Theme :: get_image_path() . 'timeframe.png"/>', false);
            $table->set_header(5, '', false);
        }
        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\teaching_assignment\Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();

        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_teaching_assignment_parameters()))
        {
            Display :: not_allowed();
        }

        $html = array();
        if (count($this->get_teaching_assignments()) > 0)
        {
            $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_teaching_assignment_parameters());

            $tabs = new DynamicTabsRenderer('teaching_assignment_list');

//             $source_tabs = new DynamicTabsRenderer('teaching_assignment_year_list');

//             if ($this->has_teaching_assignments(0, TeachingAssignment :: TYPE_TEACHER))
//             {
//                 $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: TYPE_TEACHER, Translation :: get('Teacher'), Theme :: get_image_path() . 'teacher.png', $this->get_teaching_assignments_table(0, TeachingAssignment :: TYPE_TEACHER)->toHTML()));
//             }
//             if ($this->has_teaching_assignments(0, TeachingAssignment :: TYPE_MANAGER))
//             {
//                 $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: TYPE_MANAGER, Translation :: get('Manager'), Theme :: get_image_path() . 'manager.png', $this->get_teaching_assignments_table(0, TeachingAssignment :: TYPE_MANAGER)->toHTML()));
//             }

//             $tabs->add_tab(new DynamicContentTab(0, Translation :: get('AllYears'), null, $source_tabs->render()));

            foreach ($years as $year)
            {
                $source_tabs = new DynamicTabsRenderer('teaching_assignment_year_' . $year . '_list');

                if ($this->has_teaching_assignments($year, TeachingAssignment :: TYPE_TEACHER))
                {
                    $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: TYPE_TEACHER, Translation :: get('Teacher'), Theme :: get_image_path() . 'teacher.png', $this->get_teaching_assignments_table($year, TeachingAssignment :: TYPE_TEACHER)->toHTML()));
                }

                if ($this->has_teaching_assignments($year, TeachingAssignment :: TYPE_MANAGER))
                {
                    $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: TYPE_MANAGER, Translation :: get('Manager'), Theme :: get_image_path() . 'manager.png', $this->get_teaching_assignments_table($year, TeachingAssignment :: TYPE_MANAGER)->toHTML()));
                }
                $tabs->add_tab(new DynamicContentTab($year, $year, null, $source_tabs->render()));
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