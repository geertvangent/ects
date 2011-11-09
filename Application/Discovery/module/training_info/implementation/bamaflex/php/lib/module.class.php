<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use common\libraries\ToolbarItem;

use common\libraries\StringUtilities;

use common\libraries\DynamicTabsRenderer;

use common\libraries\DynamicContentTab;

use application\discovery\module\training_info\DataManager;

use common\libraries\PropertiesTable;

use common\libraries\Request;

use common\libraries\Theme;

use application\discovery\LegendTable;

use application\discovery\SortableTable;

use common\libraries\Translation;

class Module extends \application\discovery\module\training_info\Module
{
    const PARAM_SOURCE = 'source';
    const TAB_GENERAL = 1;
    const TAB_OPTIONS = 2;
    const TAB_TRAJECTORIES = 3;

    const TAB_OPTION_CHOICES = 1;
    const TAB_OPTION_MAJORS = 2;
    const TAB_OPTION_PACKAGES = 3;

    function get_training_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_TRAINING_ID), Request :: get(self :: PARAM_SOURCE));
    }

    function render()
    {
        $html = array();
        $training = $this->get_training();

        $html[] = '<h3>';
        if ($training->get_previous_id())
        {
            $parameters = new Parameters($training->get_previous_id(), $training->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $html[] = Theme :: get_common_image('action_prev', 'png', Translation :: get('Previous'), $link, ToolbarItem :: DISPLAY_ICON);
        }
        else
        {
            $html[] = Theme :: get_common_image('action_prev_na', 'png', Translation :: get('PreviousNA'), null, ToolbarItem :: DISPLAY_ICON);

        }
        $html[] = $training->get_name();
        if ($training->get_next_id())
        {
            $parameters = new Parameters($training->get_next_id(), $training->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $html[] = Theme :: get_common_image('action_next', 'png', Translation :: get('Next'), $link, ToolbarItem :: DISPLAY_ICON);
        }
        else
        {
            $html[] = Theme :: get_common_image('action_next_na', 'png', Translation :: get('NextNA'), null, ToolbarItem :: DISPLAY_ICON);

        }
        $html[] = '</h3>';

        $tabs = new DynamicTabsRenderer('training');

        $tabs->add_tab(new DynamicContentTab(self :: TAB_GENERAL, Translation :: get('General'), Theme :: get_image_path() . 'tabs/' . self :: TAB_GENERAL . '.png', $this->get_general()));

        if ($training->has_options())
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_OPTIONS, Translation :: get('Options'), Theme :: get_image_path() . 'tabs/' . self :: TAB_OPTIONS . '.png', $this->get_options()));
        }
        if ($training->has_trajectories())
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_TRAJECTORIES, Translation :: get('Trajectories'), Theme :: get_image_path() . 'tabs/' . self :: TAB_TRAJECTORIES . '.png', $this->get_trajectories()));
        }

        $html[] = $tabs->render();
        return implode("\n", $html);
    }

    function get_general()
    {
        $training = $this->get_training();
        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $training->get_year();

        $history = array();
        $tainings = $training->get_all($this->get_module_instance());

        foreach ($tainings as $training_history)
        {
            $parameters = new Parameters($training_history->get_id(), $training_history->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $history[] = '<a href="' . $link . '">' . $training_history->get_year() . '</a>';
        }
        $properties[Translation :: get('History')] = implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $history);

        $properties[Translation :: get('BamaType')] = $training->get_bama_type_string();
        $properties[Translation :: get('Type')] = $training->get_type();
        $properties[Translation :: get('Domain')] = $training->get_domain();
        $properties[Translation :: get('Credits')] = $training->get_credits();

        $properties[Translation :: get('StartDate')] = $training->get_start_date();
        $properties[Translation :: get('EndDate')] = $training->get_end_date();
        $properties[Translation :: get('Languages')] = $training->get_languages_string();

        $table = new PropertiesTable($properties);

        $html[] = $table->toHtml();
        if (! StringUtilities :: is_null_or_empty($training->get_goals(), true))
        {
            $html[] = '<br/>';
            $html[] = '<div class="content_object" style="background-image: url(' . Theme :: get_image_path(__NAMESPACE__) . 'general/goals.png);">';
            $html[] = '<div class="title">';
            $html[] = Translation :: get('Goals');
            $html[] = '</div>';
            $html[] = '<div class="description">';
            $html[] = $training->get_goals();
            $html[] = '</div>';
            $html[] = '</div>';
        }
        return implode("\n", $html);
    }

    function get_options()
    {
        $training = $this->get_training();
        $tabs = new DynamicTabsRenderer('options');

        if ($training->has_choices())
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_OPTION_CHOICES, Translation :: get('Choices'), null, $this->get_choices()));
        }

        if ($training->has_majors())
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_OPTION_MAJORS, Translation :: get('Majors'), null, $this->get_majors()));
        }

        if ($training->has_packages())
        {
            $tabs->add_tab(new DynamicContentTab(self :: TAB_OPTION_PACKAGES, Translation :: get('Packages'), null, $this->get_packages()));
        }

        return $tabs->render();
    }

    function get_choices()
    {
        $training = $this->get_training();
        $data = array();
        $html = array();
        $html[] = '<div>';
        foreach ($training->get_choices() as $choice)
        {
            $row = array();
            $row[] = $choice->get_name();

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Choice'), false);
        $html[] = '<div style="float:left;width:48%">';
        $html[] = $table->as_html();
        $html[] = '<div class="clear"></div>';
        $html[] = '</div>';

        $data = array();
        foreach ($training->get_choice_options() as $choice_option)
        {
            $row = array();
            $row[] = $choice_option->get_name();

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('ChoiceOption'), false);
        $html[] = '<div style="float:right;width:48%">';
        $html[] = $table->as_html();
        $html[] = '<div class="clear"></div>';
        $html[] = '</div>';

        $html[] = '<div class="clear"></div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    function get_majors()
    {
        $training = $this->get_training();

        $html = array();
        $data = array();
        foreach ($training->get_majors() as $major)
        {
            $row = array();
            $row[] = $major->get_name();

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Major'), false);
        $html[] = $table->as_html();

        if ($training->has_major_choices())
        {
            $tabs = new DynamicTabsRenderer('majors');

            foreach ($training->get_majors() as $major)
            {
                if ($major->has_choices())
                {
                    $tabs->add_tab(new DynamicContentTab($major->get_id(), $major->get_name(), null, $this->get_major_choices($major)));

                }
            }

            $html[] = '<br/>' . $tabs->render();
        }
        return implode("\n", $html);
    }

    function get_major_choices($major)
    {
        $html = array();
        $data = array();

        $html[] = '<div>';
        foreach ($major->get_choices() as $choice)
        {
            $row = array();
            $row[] = $choice->get_name();

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Choice'), false);
        $html[] = '<div style="float:left;width:48%">';
        $html[] = $table->as_html();
        $html[] = '<div class="clear"></div>';
        $html[] = '</div>';

        $data = array();
        foreach ($major->get_choice_options() as $choice_option)
        {
            $row = array();
            $row[] = $choice_option->get_name();

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('ChoiceOption'), false);
        $html[] = '<div style="float:right;width:48%">';
        $html[] = $table->as_html();
        $html[] = '<div class="clear"></div>';
        $html[] = '</div>';

        $html[] = '<div class="clear"></div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    function get_packages()
    {
        $training = $this->get_training();
        $tabs = new DynamicTabsRenderer('packages');

        foreach ($training->get_packages() as $package)
        {
            $tabs->add_tab(new DynamicContentTab($package->get_id(), $package->get_name(), null, $this->get_package_courses($package)));
        }

        return $tabs->render();
    }

    function get_package_courses($package)
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                'data_source' => $data_source));

        foreach ($package->get_courses() as $course)
        {
            $row = array();
            $row[] = $course->get_credits();

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

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Credits'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');
        $table->set_header(1, Translation :: get('Course'), false);
        return $table->as_html();
    }

    function get_trajectories()
    {
        $training = $this->get_training();
        $tabs = new DynamicTabsRenderer('trajectories');

        foreach ($training->get_trajectories() as $trajectory)
        {
            $tabs->add_tab(new DynamicContentTab($trajectory->get_id(), $trajectory->get_name(), null, $this->get_sub_trajectories($trajectory)));
        }

        return $tabs->render();
    }

    function get_sub_trajectories($trajectory)
    {
        $tabs = new DynamicTabsRenderer('sub_trajectories_' . $trajectory->get_id());

        foreach ($trajectory->get_trajectories() as $trajectory)
        {
            $tabs->add_tab(new DynamicContentTab($trajectory->get_id(), $trajectory->get_name(), null, $this->get_sub_trajectory_courses($trajectory)));
        }

        return $tabs->render();
    }

    function get_sub_trajectory_courses($trajectory)
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                'data_source' => $data_source));

        foreach ($trajectory->get_courses() as $course)
        {
            $row = array();
            $row[] = $course->get_credits();

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

            $data[] = $row;
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Credits'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');
        $table->set_header(1, Translation :: get('Course'), false);
        return $table->as_html();
    }
}
?>