<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use application\discovery\SortableTable;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\PropertiesTable;
use application\discovery\LegendTable;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\DynamicVisualTab;
use common\libraries\Display;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        $html = array();
        $training = $this->get_training();
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_year()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_faculty()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_name()));
        
        $html[] = $this->get_general();
        $html[] = '</br>';
        
        $tabs = new DynamicVisualTabsRenderer('training');
        $current_tab = $this->module_parameters()->get_tab();
        switch ($current_tab)
        {
            case Module :: TAB_GOALS :
                $tabs->set_content($training->get_goals());
                break;
            case Module :: TAB_OPTIONS :
                if ($training->has_options())
                {
                    
                    $tabs->set_content($this->get_options());
                }
                else
                {
                    $tabs->set_content(Display :: warning_message(Translation :: get('NoOptions'), true));
                }
                break;
            case Module :: TAB_TRAJECTORIES :
                if ($training->has_trajectories())
                {
                    $tabs->set_content($this->get_trajectories());
                }
                else
                {
                    $tabs->set_content(Display :: warning_message(Translation :: get('NoTrajectories'), true));
                }
                break;
            case Module :: TAB_COURSES :
                if ($training->has_courses())
                {
                    $tabs->set_content($this->get_courses());
                }
                else
                {
                    $tabs->set_content(Display :: warning_message(Translation :: get('NoCourses'), true));
                }
                break;
        }
        $parameters = $this->module_parameters();
        
        $parameters->set_tab(Module :: TAB_GOALS);
        $tabs->add_tab(
            new DynamicVisualTab(
                Module :: TAB_GOALS, 
                Translation :: get('Goals'), 
                Theme :: get_image_path() . 'tabs/' . Module :: TAB_GOALS . '.png', 
                $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), 
                $current_tab == Module :: TAB_GOALS));
        $parameters->set_tab(Module :: TAB_OPTIONS);
        $tabs->add_tab(
            new DynamicVisualTab(
                Module :: TAB_OPTIONS, 
                Translation :: get('Options'), 
                Theme :: get_image_path() . 'tabs/' . Module :: TAB_OPTIONS . '.png', 
                $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), 
                $current_tab == Module :: TAB_OPTIONS));
        $parameters->set_tab(Module :: TAB_TRAJECTORIES);
        $tabs->add_tab(
            new DynamicVisualTab(
                Module :: TAB_TRAJECTORIES, 
                Translation :: get('Trajectories'), 
                Theme :: get_image_path() . 'tabs/' . Module :: TAB_TRAJECTORIES . '.png', 
                $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), 
                $current_tab == Module :: TAB_TRAJECTORIES));
        $parameters->set_tab(Module :: TAB_COURSES);
        $tabs->add_tab(
            new DynamicVisualTab(
                Module :: TAB_COURSES, 
                Translation :: get('Courses'), 
                Theme :: get_image_path() . 'tabs/' . Module :: TAB_COURSES . '.png', 
                $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), 
                $current_tab == Module :: TAB_COURSES));
        
        $html[] = $tabs->render();
        return implode("\n", $html);
    }

    public function get_general()
    {
        $training = $this->get_training();
        $properties = array();
        $properties[Translation :: get('Year')] = $training->get_year();
        
        $history = array();
        $trainings = $training->get_all($this->get_module_instance());
        
        $i = 1;
        foreach ($trainings as $year => $year_trainings)
        {
            if (count($year_trainings) > 1)
            {
                $multi_history = array();
                
                foreach ($year_trainings as $year_training)
                {
                    $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                    $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                    $multi_history[] = '<a href="' . $link . '">' . $year_training->get_name() . '</a>';
                }
                
                if ($i == 1)
                {
                    $previous_history = array($year, implode('  |  ', $multi_history));
                }
                else
                {
                    $next_history = array($year, implode('  |  ', $multi_history));
                }
            }
            else
            {
                $year_training = $year_trainings[0];
                
                $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                
                if ($year_training->has_previous_references() && ! $year_training->has_previous_references(true))
                {
                    if ($i == 1)
                    {
                        $previous_history = array(
                            $year, 
                            '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                 $year_training->get_name() . '</a>');
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $next_history = array(
                            $year, 
                            '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                 $year_training->get_name() . '</a>');
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                             $year_training->get_year() . '</a>';
                    }
                }
                elseif ($year_training->has_next_references() && ! $year_training->has_next_references(true))
                {
                    if ($i == 1)
                    {
                        $previous_history = array(
                            $year, 
                            '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                 $year_training->get_name() . '</a>');
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $next_history = array(
                            $year, 
                            '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                 $year_training->get_name() . '</a>');
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                             $year_training->get_year() . '</a>';
                    }
                }
                else
                {
                    $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                    $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                    $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                         $year_training->get_year() . '</a>';
                }
            }
            $i ++;
        }
        
        $properties[Translation :: get('History')] = implode('  |  ', $history);
        
        if ($previous_history)
        {
            $properties[Translation :: get('HistoryWas', array('YEAR' => $previous_history[0]), 'application\discovery')] = $previous_history[1];
        }
        
        if ($next_history)
        {
            $properties[Translation :: get('HistoryBecomes', array('YEAR' => $next_history[0]), 'application\discovery')] = $next_history[1];
        }
        
        $properties[Translation :: get('BamaType')] = $training->get_bama_type_string();
        $properties[Translation :: get('Type')] = $training->get_type();
        $properties[Translation :: get('Domain')] = $training->get_domain();
        $properties[Translation :: get('Credits')] = $training->get_credits();
        
        $properties[Translation :: get('StartDate')] = $training->get_start_date();
        $properties[Translation :: get('EndDate')] = $training->get_end_date();
        $properties[Translation :: get('Languages')] = $training->get_languages_string();
        
        $groups = array();
        foreach ($training->get_groups() as $group)
        {
            $groups[] = $group->get_group() . ' <em>(' . $group->get_group_id() . ')</em>';
        }
        
        if (count($groups) > 0)
        {
            $properties[Translation :: get('Groups')] = implode('<br />', $groups);
        }
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        if ($photo_module_instance)
        {
            $buttons = array();
            // students
            $parameters = new \application\discovery\module\photo\Parameters();
            $parameters->set_training_id($training->get_id());
            $parameters->set_type(\application\discovery\module\photo\Module :: TYPE_STUDENT);
            
            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
            $image = Theme :: get_image(
                'type/2', 
                'png', 
                Translation :: get('Students', null, 'application\discovery\module\photo'), 
                $url, 
                ToolbarItem :: DISPLAY_ICON, 
                false, 
                'application\discovery\module\photo');
            $buttons[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image, 
                Translation :: get('Students', null, 'application\discovery\module\photo
                    '), 
                Translation :: get('TypeName', null, 'application\discovery\module\photo'));
            
            // teachers
            $parameters = new \application\discovery\module\photo\Parameters();
            $parameters->set_training_id($training->get_id());
            $parameters->set_type(\application\discovery\module\photo\Module :: TYPE_TEACHER);
            
            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
            $image = Theme :: get_image(
                'type/1', 
                'png', 
                Translation :: get('Teachers', null, 'application\discovery\module\photo
                    '), 
                $url, 
                ToolbarItem :: DISPLAY_ICON, 
                false, 
                'application\discovery\module\photo');
            $buttons[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image, 
                Translation :: get('Teachers', null, 'application\discovery\module\photo
                    '), 
                Translation :: get('TypeName', null, 'application\discovery\module\photo'));
            
            $properties[Translation :: get('Photos')] = implode("\n", $buttons);
        }
        
        $training_results_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\training_results\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        if ($training_results_module_instance)
        {
            $parameters = new \application\discovery\module\training_results\implementation\bamaflex\Parameters();
            $parameters->set_training_id($training->get_id());
            $parameters->set_source($training->get_source());
            
            $url = $this->get_instance_url($training_results_module_instance->get_id(), $parameters);
            $image = Theme :: get_image(
                'logo/16', 
                'png', 
                Translation :: get(
                    'TypeName', 
                    null, 
                    'application\discovery\module\training_results\implementation\bamaflex'), 
                $url, 
                ToolbarItem :: DISPLAY_ICON, 
                false, 
                'application\discovery\module\training_results\implementation\bamaflex');
            $properties[Translation :: get(
                'TypeName', 
                null, 
                'application\discovery\module\training_results\implementation\bamaflex')] = $image;
        }
        $table = new PropertiesTable($properties);
        
        return $table->toHtml();
    }

    public function get_options()
    {
        $training = $this->get_training();
        $tabs = new DynamicTabsRenderer('options');
        
        if ($training->has_choices())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module :: TAB_OPTION_CHOICES, 
                    Translation :: get('Choices'), 
                    null, 
                    $this->get_choices()));
        }
        
        if ($training->has_majors())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module :: TAB_OPTION_MAJORS, 
                    Translation :: get('Majors'), 
                    null, 
                    $this->get_majors()));
        }
        
        if ($training->has_packages())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    Module :: TAB_OPTION_PACKAGES, 
                    Translation :: get('Packages'), 
                    null, 
                    $this->get_packages()));
        }
        
        return $tabs->render();
    }

    public function get_choices()
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

    public function get_majors()
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
                    $tabs->add_tab(
                        new DynamicContentTab(
                            $major->get_id(), 
                            $major->get_name(), 
                            null, 
                            $this->get_major_choices($major)));
                }
            }
            
            $html[] = '<br/>' . $tabs->render();
        }
        return implode("\n", $html);
    }

    public function get_packages()
    {
        $training = $this->get_training();
        $tabs = new DynamicTabsRenderer('packages');
        
        foreach ($training->get_packages() as $package)
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    $package->get_id(), 
                    $package->get_name(), 
                    null, 
                    $this->get_package_courses($package)));
        }
        
        return $tabs->render();
    }

    public function get_major_choices($major)
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

    public function get_package_courses($package)
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        $course_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $course_result_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course_results\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($package->get_courses() as $course)
        {
            $row = array();
            $row[] = $course->get_credits();
            
            if ($course_module_instance)
            {
                $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                    $course->get_programme_id(), 
                    $course->get_source());
                $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
            }
            else
            {
                $row[] = $course->get_name();
            }
            if ($photo_module_instance || $course_result_module_instance)
            {
                $buttons = array();
                
                if ($photo_module_instance)
                {
                    $parameters = new \application\discovery\module\photo\Parameters();
                    $parameters->set_programme_id($course->get_programme_id());
                    
                    $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image(
                        'logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'application\discovery\module\photo\implementation\bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'application\discovery\module\photo\implementation\bamaflex');
                }
                
                if ($course_result_module_instance)
                {
                    $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                        $course->get_programme_id(), 
                        $course->get_source());
                    $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image(
                        'logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'application\discovery\module\course_results\implementation\bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'application\discovery\module\course_results\implementation\bamaflex');
                }
                
                $row[] = implode("\n", $buttons);
            }
            
            $data[] = $row;
            
            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $row = array();
                    $row[] = '<span class="course_child_text">' . $child->get_credits() . '</span>';
                    
                    if ($course_module_instance)
                    {
                        $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                            $child->get_programme_id(), 
                            $child->get_source());
                        $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                        $row[] = '<span class="course_child_link"><a href="' . $url . '">' . $child->get_name() .
                             '</a></span>';
                    }
                    else
                    {
                        $row[] = '<span class="course_child_link">' . $child->get_name() . '</span>';
                    }
                    if ($photo_module_instance || $course_result_module_instance)
                    {
                        $buttons = array();
                        
                        if ($photo_module_instance)
                        {
                            $parameters = new \application\discovery\module\photo\Parameters();
                            $parameters->set_programme_id($course->get_programme_id());
                            
                            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                            $buttons[] = Theme :: get_image(
                                'logo/16', 
                                'png', 
                                Translation :: get(
                                    'TypeName', 
                                    null, 
                                    'application\discovery\module\photo\implementation\bamaflex'), 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON, 
                                false, 
                                'application\discovery\module\photo\implementation\bamaflex');
                        }
                        
                        if ($course_result_module_instance)
                        {
                            $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                                $child->get_programme_id(), 
                                $child->get_source());
                            $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                            $buttons[] = Theme :: get_image(
                                'logo/16', 
                                'png', 
                                Translation :: get(
                                    'TypeName', 
                                    null, 
                                    'application\discovery\module\course_results\implementation\bamaflex'), 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON, 
                                false, 
                                'application\discovery\module\course_results\implementation\bamaflex');
                        }
                        
                        $row[] = implode("\n", $buttons);
                    }
                    
                    $data[] = $row;
                }
            }
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Credits'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');
        $table->set_header(1, Translation :: get('Course'), false);
        $table->set_header(2, null, false);
        return $table->as_html();
    }

    public function get_trajectories()
    {
        $training = $this->get_training();
        $tabs = new DynamicTabsRenderer('trajectories');
        
        foreach ($training->get_trajectories() as $trajectory)
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    $trajectory->get_id(), 
                    $trajectory->get_name(), 
                    null, 
                    $this->get_sub_trajectories($trajectory)));
        }
        
        return $tabs->render();
    }

    public function get_sub_trajectories($trajectory)
    {
        $tabs = new DynamicTabsRenderer('sub_trajectories_' . $trajectory->get_id());
        
        foreach ($trajectory->get_trajectories() as $trajectory)
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    $trajectory->get_id(), 
                    $trajectory->get_name(), 
                    null, 
                    $this->get_sub_trajectory_courses($trajectory)));
        }
        
        return $tabs->render();
    }

    public function get_sub_trajectory_courses($trajectory)
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        $course_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $course_result_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course_results\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($trajectory->get_courses() as $course)
        {
            $row = array();
            $row[] = $course->get_credits();
            
            if ($course_module_instance)
            {
                $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                    $course->get_programme_id(), 
                    $course->get_source());
                $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
            }
            else
            {
                $row[] = $course->get_name();
            }
            
            if ($photo_module_instance || $course_result_module_instance)
            {
                $buttons = array();
                
                if ($photo_module_instance)
                {
                    $parameters = new \application\discovery\module\photo\Parameters();
                    $parameters->set_programme_id($course->get_programme_id());
                    
                    $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image(
                        'logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'application\discovery\module\photo\implementation\bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'application\discovery\module\photo\implementation\bamaflex');
                }
                
                if ($course_result_module_instance)
                {
                    $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                        $course->get_programme_id(), 
                        $course->get_source());
                    $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image(
                        'logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'application\discovery\module\course_results\implementation\bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'application\discovery\module\course_results\implementation\bamaflex');
                }
                
                $row[] = implode("\n", $buttons);
            }
            
            $data[] = $row;
            
            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $row = array();
                    $row[] = '<span class="course_child_text">' . $child->get_credits() . '</span>';
                    
                    if ($course_module_instance)
                    {
                        $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                            $child->get_programme_id(), 
                            $child->get_source());
                        $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                        $row[] = '<span class="course_child_link"><a href="' . $url . '">' . $child->get_name() .
                             '</a></span>';
                    }
                    else
                    {
                        $row[] = '<span class="course_child_link">' . $child->get_name() . '</span>';
                    }
                    
                    if ($photo_module_instance || $course_result_module_instance)
                    {
                        $buttons = array();
                        
                        if ($photo_module_instance)
                        {
                            $parameters = new \application\discovery\module\photo\Parameters();
                            $parameters->set_programme_id($course->get_programme_id());
                            
                            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                            $buttons[] = Theme :: get_image(
                                'logo/16', 
                                'png', 
                                Translation :: get(
                                    'TypeName', 
                                    null, 
                                    'application\discovery\module\photo\implementation\bamaflex'), 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON, 
                                false, 
                                'application\discovery\module\photo\implementation\bamaflex');
                        }
                        
                        if ($course_result_module_instance)
                        {
                            $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                                $child->get_programme_id(), 
                                $child->get_source());
                            $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                            $buttons[] = Theme :: get_image(
                                'logo/16', 
                                'png', 
                                Translation :: get(
                                    'TypeName', 
                                    null, 
                                    'application\discovery\module\course_results\implementation\bamaflex'), 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON, 
                                false, 
                                'application\discovery\module\course_results\implementation\bamaflex');
                        }
                        
                        $row[] = implode("\n", $buttons);
                    }
                    
                    $data[] = $row;
                }
            }
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Credits'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');
        $table->set_header(1, Translation :: get('Course'), false);
        $table->set_header(2, null, false);
        return $table->as_html();
    }

    public function get_courses()
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course\implementation\bamaflex', 
            array('data_source' => $data_source));
        $photo_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        $course_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $course_result_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course_results\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($this->get_training()->get_courses() as $course)
        {
            $row = array();
            $row[] = $course->get_credits();
            
            if ($course_module_instance)
            {
                $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                    $course->get_id(), 
                    $course->get_source());
                $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
            }
            else
            {
                $row[] = $course->get_name();
            }
            
            if ($photo_module_instance || $course_result_module_instance)
            {
                $buttons = array();
                
                if ($photo_module_instance)
                {
                    $parameters = new \application\discovery\module\photo\Parameters();
                    $parameters->set_programme_id($course->get_id());
                    
                    $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image(
                        'logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'application\discovery\module\photo\implementation\bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'application\discovery\module\photo\implementation\bamaflex');
                }
                
                if ($course_result_module_instance)
                {
                    $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                        $course->get_id(), 
                        $course->get_source());
                    $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image(
                        'logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'application\discovery\module\course_results\implementation\bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'application\discovery\module\course_results\implementation\bamaflex');
                }
                
                $row[] = implode("\n", $buttons);
            }
            
            $data[] = $row;
            
            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $row = array();
                    $row[] = '<span class="course_child_text">' . $child->get_credits() . '</span>';
                    
                    if ($course_module_instance)
                    {
                        $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                            $child->get_id(), 
                            $child->get_source());
                        $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                        $row[] = '<span class="course_child_link"><a href="' . $url . '">' . $child->get_name() .
                             '</a></span>';
                    }
                    else
                    {
                        $row[] = '<span class="course_child_link">' . $child->get_name() . '</span>';
                    }
                    
                    if ($photo_module_instance || $course_result_module_instance)
                    {
                        $buttons = array();
                        
                        if ($photo_module_instance)
                        {
                            $parameters = new \application\discovery\module\photo\Parameters();
                            $parameters->set_programme_id($child->get_id());
                            
                            $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                            $buttons[] = Theme :: get_image(
                                'logo/16', 
                                'png', 
                                Translation :: get(
                                    'TypeName', 
                                    null, 
                                    'application\discovery\module\photo\implementation\bamaflex'), 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON, 
                                false, 
                                'application\discovery\module\photo\implementation\bamaflex');
                        }
                        
                        if ($course_result_module_instance)
                        {
                            $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                                $child->get_id(), 
                                $child->get_source());
                            $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                            $buttons[] = Theme :: get_image(
                                'logo/16', 
                                'png', 
                                Translation :: get(
                                    'TypeName', 
                                    null, 
                                    'application\discovery\module\course_results\implementation\bamaflex'), 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON, 
                                false, 
                                'application\discovery\module\course_results\implementation\bamaflex');
                        }
                        $row[] = implode("\n", $buttons);
                    }
                    
                    $data[] = $row;
                }
            }
        }
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Credits'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');
        $table->set_header(1, Translation :: get('Course'), false);
        $table->set_header(2, '', false);
        return $table->as_html();
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
