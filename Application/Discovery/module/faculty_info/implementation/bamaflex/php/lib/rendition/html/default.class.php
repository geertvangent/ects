<?php
namespace application\discovery\module\faculty_info\implementation\bamaflex;

use application\discovery\SortableTable;
use libraries\format\PropertiesTable;
use libraries\format\ToolbarItem;
use application\discovery\LegendTable;
use libraries\format\Theme;
use libraries\platform\translation\Translation;
use libraries\format\Breadcrumb;
use libraries\format\BreadcrumbTrail;
use libraries\format\Display;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        
        $html = array();
        
        $html[] = $this->get_context();
        $html[] = $this->get_trainings_table()->toHTML();
        
        return implode("\n", $html);
    }

    public function get_context()
    {
        $html = array();
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $this->get_faculty()->get_year()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $this->get_faculty()->get_name()));
        
        $html[] = $this->get_faculty_properties_table()->toHtml();
        $html[] = '<br/>';
        
        return implode("\n", $html);
    }

    public function get_faculty_properties_table()
    {
        $properties = array();
        $properties[Translation :: get('Year')] = $this->get_faculty()->get_year();
        $properties[Translation :: get('Deans')] = $this->get_faculty()->get_deans_string();
        
        $history = array();
        $faculties = $this->get_faculty()->get_all($this->get_module_instance());
        
        $i = 1;
        foreach ($faculties as $year => $year_faculties)
        {
            if (count($year_faculties) > 1)
            {
                $multi_history = array();
                
                foreach ($year_faculties as $faculty)
                {
                    $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
                    
                    $is_allowed = Rights :: is_allowed(
                        Rights :: VIEW_RIGHT, 
                        $this->get_module_instance()->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $multi_history[] = '<a href="' . $link . '">' . $faculty->get_name() . '</a>';
                    }
                    else
                    {
                        $multi_history[] = $faculty->get_name();
                    }
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
                $faculty = $year_faculties[0];
                
                $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
                $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                
                if ($faculty->has_previous_references() && ! $faculty->has_previous_references(true))
                {
                    if ($i == 1)
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT, 
                            $this->get_module_instance()->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $previous_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $previous_history = array($year, $faculty->get_name());
                        }
                    }
                    elseif ($i == count($faculties))
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT, 
                            $this->get_module_instance()->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $next_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $next_history = array($year, $faculty->get_name());
                        }
                    }
                    else
                    {
                        $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
                        
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT, 
                            $this->get_module_instance()->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                            $history[] = '<a href="' . $link . '" title="' . $faculty->get_name() . '">' .
                                 $faculty->get_year() . '</a>';
                        }
                        else
                        {
                            $history[] = $faculty->get_year();
                        }
                    }
                }
                elseif ($faculty->has_next_references() && ! $faculty->has_next_references(true))
                {
                    if ($i == 1)
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT, 
                            $this->get_module_instance()->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $previous_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $previous_history = array($year, $faculty->get_name());
                        }
                    }
                    elseif ($i == count($faculties))
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT, 
                            $this->get_module_instance()->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $next_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $next_history = array($year, $faculty->get_name());
                        }
                    }
                    else
                    {
                        $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
                        
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT, 
                            $this->get_module_instance()->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                            $history[] = '<a href="' . $link . '" title="' . $faculty->get_name() . '">' .
                                 $faculty->get_year() . '</a>';
                        }
                        else
                        {
                            $history[] = $faculty->get_year();
                        }
                    }
                }
                else
                {
                    $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
                    
                    $is_allowed = Rights :: is_allowed(
                        Rights :: VIEW_RIGHT, 
                        $this->get_module_instance()->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $faculty->get_name() . '">' .
                             $faculty->get_year() . '</a>';
                    }
                    else
                    {
                        $history[] = $faculty->get_year();
                    }
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
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        if ($photo_module_instance)
        {
            $is_allowed = \application\discovery\module\photo\implementation\bamaflex\Rights :: is_allowed(
                \application\discovery\module\photo\implementation\bamaflex\Rights :: VIEW_RIGHT, 
                $photo_module_instance->get_id(), 
                $parameters);
            
            $buttons = array();
            
            if ($is_allowed)
            {
                // students
                $parameters = new \application\discovery\module\photo\Parameters();
                $parameters->set_faculty_id($this->get_faculty()->get_id());
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
                $parameters->set_faculty_id($this->get_faculty()->get_id());
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
                
                // Employees
                $parameters = new \application\discovery\module\photo\Parameters();
                $parameters->set_faculty_id($this->get_faculty()->get_id());
                $parameters->set_type(\application\discovery\module\photo\Module :: TYPE_EMPLOYEE);
                
                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                $image = Theme :: get_image(
                    'type/3', 
                    'png', 
                    Translation :: get('Employees', null, 'application\discovery\module\photo
                    '), 
                    $url, 
                    ToolbarItem :: DISPLAY_ICON, 
                    false, 
                    'application\discovery\module\photo');
                $buttons[] = $image;
                
                LegendTable :: get_instance()->add_symbol(
                    $image, 
                    Translation :: get('Employees', null, 'application\discovery\module\photo
                    '), 
                    Translation :: get('TypeName', null, 'application\discovery\module\photo'));
            }
            else
            {
                // students
                $image = Theme :: get_image(
                    'type/2_na', 
                    'png', 
                    Translation :: get('StudentsNotAvailable', null, 'application\discovery\module\photo'), 
                    null, 
                    ToolbarItem :: DISPLAY_ICON, 
                    false, 
                    'application\discovery\module\photo');
                $buttons[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image, 
                    Translation :: get(
                        'StudentsNotAvailable', 
                        null, 
                        'application\discovery\module\photo
                    '), 
                    Translation :: get('TypeName', null, 'application\discovery\module\photo'));
                
                // teachers
                $image = Theme :: get_image(
                    'type/1_na', 
                    'png', 
                    Translation :: get(
                        'TeachersNotAvailable', 
                        null, 
                        'application\discovery\module\photo
                    '), 
                    null, 
                    ToolbarItem :: DISPLAY_ICON, 
                    false, 
                    'application\discovery\module\photo');
                $buttons[] = $image;
                
                LegendTable :: get_instance()->add_symbol(
                    $image, 
                    Translation :: get(
                        'TeachersNotAvailable', 
                        null, 
                        'application\discovery\module\photo
                    '), 
                    Translation :: get('TypeName', null, 'application\discovery\module\photo'));
                
                // Employees
                $image = Theme :: get_image(
                    'type/3_na', 
                    'png', 
                    Translation :: get(
                        'EmployeesNotAvailable', 
                        null, 
                        'application\discovery\module\photo
                    '), 
                    null, 
                    ToolbarItem :: DISPLAY_ICON, 
                    false, 
                    'application\discovery\module\photo');
                $buttons[] = $image;
                
                LegendTable :: get_instance()->add_symbol(
                    $image, 
                    Translation :: get(
                        'EmployeesNotAvailable', 
                        null, 
                        'application\discovery\module\photo
                    '), 
                    Translation :: get('TypeName', null, 'application\discovery\module\photo'));
            }
            
            $properties[Translation :: get('Photos')] = implode("\n", $buttons);
        }
        return new PropertiesTable($properties);
    }

    public function get_trainings_table()
    {
        $trainings = $this->get_trainings_data($this->get_module_parameters());
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_info_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\training_info\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $group_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\group\implementation\bamaflex', 
            array('data_source' => $data_source));
        $photo_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($trainings as $key => $training)
        {
            $row = array();
            
            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters(
                    $training->get_id(), 
                    $training->get_source());
                
                $is_allowed = \application\discovery\module\training_info\implementation\bamaflex\Rights :: is_allowed(
                    \application\discovery\module\training_info\implementation\bamaflex\Rights :: VIEW_RIGHT, 
                    $training_info_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $training->get_name() . '</a>';
                }
                else
                {
                    $row[] = $training->get_name();
                }
            }
            else
            {
                $row[] = $training->get_name();
            }
            
            $row[] = $training->get_domain();
            $row[] = $training->get_credits();
            
            $bama_type_image = '<img src="' .
                 Theme :: get_image_path('application\discovery\module\training\implementation\bamaflex') . 'bama_type/' .
                 $training->get_bama_type() . '.png" alt="' .
                 Translation :: get(
                    $training->get_bama_type_string(), 
                    null, 
                    'application\discovery\module\training\implementation\bamaflex') . '" title="' . Translation :: get(
                    $training->get_bama_type_string(), 
                    null, 
                    'application\discovery\module\training\implementation\bamaflex') . '" />';
            $row[] = $bama_type_image;
            LegendTable :: get_instance()->add_symbol(
                $bama_type_image, 
                Translation :: get(
                    $training->get_bama_type_string(), 
                    null, 
                    'application\discovery\module\training\implementation\bamaflex'), 
                Translation :: get('BamaType', null, 'application\discovery\module\training\implementation\bamaflex'));
            
            if ($group_module_instance || $photo_module_instance)
            {
                $buttons = array();
                
                if ($group_module_instance)
                {
                    $parameters = new \application\discovery\module\group\implementation\bamaflex\Parameters(
                        $training->get_id(), 
                        $training->get_source());
                    
                    $is_allowed = \application\discovery\module\group\implementation\bamaflex\Rights :: is_allowed(
                        \application\discovery\module\group\implementation\bamaflex\Rights :: VIEW_RIGHT, 
                        $group_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($group_module_instance->get_id(), $parameters);
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('Groups'), 
                            Theme :: get_image_path('application\discovery\module\group\implementation\bamaflex') .
                                 'logo/16.png', 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON);
                    }
                    else
                    {
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('GroupsNotAvailable'), 
                            Theme :: get_image_path('application\discovery\module\group\implementation\bamaflex') .
                                 'logo/16_na.png', 
                                null, 
                                ToolbarItem :: DISPLAY_ICON);
                    }
                    
                    $buttons[] = $toolbar_item->as_html();
                }
                
                if ($photo_module_instance)
                {
                    $parameters = new \application\discovery\module\photo\Parameters();
                    $parameters->set_training_id($training->get_id());
                    
                    $is_allowed = \application\discovery\module\photo\implementation\bamaflex\Rights :: is_allowed(
                        \application\discovery\module\photo\implementation\bamaflex\Rights :: VIEW_RIGHT, 
                        $photo_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        
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
                    else
                    {
                        $buttons[] = Theme :: get_image(
                            'logo/16_na', 
                            'png', 
                            Translation :: get(
                                'TypeName', 
                                null, 
                                'application\discovery\module\photo\implementation\bamaflex'), 
                            null, 
                            ToolbarItem :: DISPLAY_ICON, 
                            false, 
                            'application\discovery\module\photo\implementation\bamaflex');
                    }
                }
                
                $training_results_module_instance = \application\discovery\Module :: exists(
                    'application\discovery\module\training_results\implementation\bamaflex', 
                    array('data_source' => $data_source));
                
                if ($training_results_module_instance)
                {
                    $parameters = new \application\discovery\module\training_results\implementation\bamaflex\Parameters();
                    $parameters->set_training_id($training->get_id());
                    $parameters->set_source($training->get_source());
                    
                    $is_allowed = \application\discovery\module\training_results\implementation\bamaflex\Rights :: is_allowed(
                        \application\discovery\module\training_results\implementation\bamaflex\Rights :: VIEW_RIGHT, 
                        $training_results_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($training_results_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: get_image(
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
                    }
                    else
                    {
                        $buttons[] = Theme :: get_image(
                            'logo/16_na', 
                            'png', 
                            Translation :: get(
                                'TypeName', 
                                null, 
                                'application\discovery\module\training_results\implementation\bamaflex'), 
                            null, 
                            ToolbarItem :: DISPLAY_ICON, 
                            false, 
                            'application\discovery\module\training_results\implementation\bamaflex');
                    }
                }
                $row[] = implode("\n", $buttons);
            }
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('Name'), false);
        $table->set_header(1, Translation :: get('Domain'), false);
        $table->set_header(2, Translation :: get('Credits'), false);
        $table->set_header(3, '', false);
        $table->set_header(4, '', false);
        
        return $table;
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
