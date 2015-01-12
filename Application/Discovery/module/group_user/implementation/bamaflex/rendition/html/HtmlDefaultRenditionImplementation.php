<?php
namespace Application\Discovery\module\group_user\implementation\bamaflex\rendition\html;

use libraries\format\theme\Theme;
use libraries\format\DynamicContentTab;
use libraries\format\DynamicTabsRenderer;
use libraries\format\structure\Toolbar;
use libraries\format\PropertiesTable;
use libraries\format\Breadcrumb;
use libraries\format\BreadcrumbTrail;
use application\discovery\SortableTable;
use libraries\platform\translation\Translation;
use libraries\format\Display;
use application\discovery\module\group_user\DataManager;
use application\discovery\module\group\implementation\bamaflex\Group;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{
    /*
     * (non-PHPdoc) @see application\discovery\module\group_user.Module::render()
     */
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
        $html[] = $this->get_group_properties_table();
        if (count($this->get_group_user()) > 0)
        {
            $html[] = $this->get_group_user_table();
            
            \application\discovery\HtmlDefaultRendition :: add_export_action($this);
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }

    public function get_group_properties_table()
    {
        $group = DataManager :: get_instance($this->get_module_instance())->retrieve_group(
            Module :: get_group_parameters());
        
        $properties = array();
        $properties[Translation :: get('Year')] = $group->get_year();
        $properties[Translation :: get('Code')] = $group->get_code();
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $group->get_description()));
        $table = new PropertiesTable($properties);
        
        return $table->toHtml();
    }

    public function get_group_user_table()
    {
        $data = array();
        $data_struck = array();
        
        $cache = array();
        
        foreach ($this->get_group_user() as $group_user)
        {
            
            if ($group_user->get_struck() == 0)
            {
                $data[] = $this->get_row($group_user);
            }
            else
            {
                $data_struck[] = $this->get_row($group_user);
            }
            $cache[$group_user->get_struck()][] = $group_user->get_person_id();
        }
        
        $course_data = array();
        $course_data_struck = array();
        
        if ($this->get_module_parameters()->get_type() == Group :: TYPE_CLASS)
        {
            $parameters = $this->get_module_parameters();
            $parameters->set_type(Group :: TYPE_CLASS_COURSE);
            $class_course_users = $this->get_data_manager()->retrieve_group_users($parameters);
            
            foreach ($class_course_users as $course_user)
            {
                if (! in_array($course_user->get_person_id(), $cache[$course_user->get_struck()]))
                {
                    if ($course_user->get_struck() == 0)
                    {
                        $course_data[] = $this->get_row($course_user);
                    }
                    else
                    {
                        $course_data_struck[] = $this->get_row($course_user);
                    }
                }
            }
        }
        $tabs = new DynamicTabsRenderer('group_user');
        
        if (count($data) > 0 || count($course_data) > 0)
        {
            $html = array();
            $html[] = $this->get_table($data);
            if (count($course_data) > 0)
            {
                $html[] = '<br/><h3>' . Translation :: get('ClassCourse') . '</h3>';
                $html[] = $this->get_table($course_data);
            }
            
            $tabs->add_tab(
                new DynamicContentTab(
                    0, 
                    Translation :: get('Enrolled'), 
                    Theme :: get_image_path() . 'type/0.png', 
                    implode("\n", $html)));
        }
        
        if (count($data_struck) > 0 || count($course_data_struck) > 0)
        {
            $html = array();
            $html[] = $this->get_table($data_struck);
            if (count($course_data_struck) > 0)
            {
                $html[] = '<br/><h3>' . Translation :: get('ClassCourse') . '</h3>';
                $html[] = $this->get_table($course_data_struck);
            }
            
            $tabs->add_tab(
                new DynamicContentTab(
                    1, 
                    Translation :: get('Struck'), 
                    Theme :: get_image_path() . 'type/1.png', 
                    implode("\n", $html)));
        }
        
        return $tabs->render();
    }

    public function get_row($group_user)
    {
        $row = array();
        $row[] = $group_user->get_last_name();
        $row[] = $group_user->get_first_name();
        
        $toolbar = new Toolbar();
        
        $user = \core\user\DataManager :: retrieve_user_by_official_code($group_user->get_person_id());
        if ($user instanceof \core\user\User)
        {
            $profile_link = $this->get_module_link(
                'application\discovery\module\profile\implementation\bamaflex', 
                $user->get_id());
            if ($profile_link)
            {
                $toolbar->add_item($profile_link);
            }
        }
        $row[] = $toolbar->as_html();
        
        return $row;
    }

    public function get_table($data)
    {
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('FirstName'), false);
        $table->set_header(1, Translation :: get('LastName'), false);
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
