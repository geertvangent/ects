<?php
namespace application\discovery\module\group\implementation\bamaflex;

use common\libraries\PropertiesTable;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\Display;
use application\discovery\SortableTable;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use application\discovery\module\group\DataManager;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        // BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));
        $html = array();
        
        if ($this->has_groups())
        {
            $tabs = new DynamicTabsRenderer('group');
            
            if ($this->has_groups(Group :: TYPE_CLASS))
            {
                $tabs->add_tab(
                        new DynamicContentTab(Group :: TYPE_CLASS, 
                                Translation :: get(Group :: type_string(Group :: TYPE_CLASS)), 
                                Theme :: get_image_path() . 'type/' . Group :: TYPE_CLASS . '.png', 
                                $this->get_groups_table(Group :: TYPE_CLASS)->as_html()));
            }
            if ($this->has_groups(Group :: TYPE_CUSTOM))
            {
                $tabs->add_tab(
                        new DynamicContentTab(Group :: TYPE_CUSTOM, 
                                Translation :: get(Group :: type_string(Group :: TYPE_CUSTOM)), 
                                Theme :: get_image_path() . 'type/' . Group :: TYPE_CUSTOM . '.png', 
                                $this->get_groups_table(Group :: TYPE_CUSTOM)->as_html()));
            }
            if ($this->has_groups(Group :: TYPE_TRAINING))
            {
                $tabs->add_tab(
                        new DynamicContentTab(Group :: TYPE_TRAINING, 
                                Translation :: get(Group :: type_string(Group :: TYPE_TRAINING)), 
                                Theme :: get_image_path() . 'type/' . Group :: TYPE_TRAINING . '.png', 
                                $this->get_groups_table(Group :: TYPE_TRAINING)->as_html()));
            }
            $html[] = $this->get_training_properties_table() . '</br>';
            
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }

    public function get_groups_table($type)
    {
        $groups = $this->get_groups_data($type);
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $group_user_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\group_user\implementation\bamaflex', array('data_source' => $data_source));
        
        foreach ($groups as $key => $group)
        {
            $row = array();
            $row[] = $group->get_code();
            $row[] = $group->get_description();
            
            if ($group_user_module_instance)
            {
                $parameters = new \application\discovery\module\group_user\implementation\bamaflex\Parameters(
                        $group->get_type_id(), $group->get_source(), $group->get_type());
                $url = $this->get_instance_url($group_user_module_instance->get_id(), $parameters);
                $toolbar_item = new ToolbarItem(Translation :: get('Users'), 
                        Theme :: get_image_path('application\discovery\module\group_user\implementation\bamaflex') . 'logo/16.png', 
                        $url, ToolbarItem :: DISPLAY_ICON);
                
                $row[] = $toolbar_item->as_html();
            }
            else
            {
                $row[] = ' ';
            }
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('Code'), false);
        $table->getHeader()->setColAttributes(0, 'class="code"');
        
        $table->set_header(1, Translation :: get('Description'), false);
        
        $table->set_header(2, ' ', false);
        
        return $table;
    }

    public function get_training_properties_table()
    {
        $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                Module :: get_training_info_parameters());
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        
        $faculty_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\faculty_info\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        $html = array();
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
                        $previous_history = array($year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $next_history = array($year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_year() . '</a>';
                    }
                }
                elseif ($year_training->has_next_references() && ! $year_training->has_next_references(true))
                {
                    if ($i == 1)
                    {
                        $previous_history = array($year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $next_history = array($year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_year() . '</a>';
                    }
                }
                else
                {
                    $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                    $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                    $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_year() . '</a>';
                }
            }
            $i ++;
        }
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_year()));
        
        $properties[Translation :: get('History')] = implode('  |  ', $history);
        
        if ($previous_history)
        {
            $properties[Translation :: get('HistoryWas', array('YEAR' => $previous_history[0]), 'application\discovery')] = $previous_history[1];
        }
        
        if ($next_history)
        {
            $properties[Translation :: get('HistoryBecomes', array('YEAR' => $next_history[0]), 'application\discovery')] = $next_history[1];
        }
        
        if ($faculty_info_module_instance)
        {
            $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters(
                    $training->get_faculty_id(), $training->get_source());
            $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Faculty')] = '<a href="' . $url . '">' . $training->get_faculty() . '</a>';
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb($url, $training->get_faculty()));
        }
        else
        {
            $properties[Translation :: get('Faculty')] = $training->get_faculty();
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_faculty()));
        }
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_name()));
        
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode("\n", $html);
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
