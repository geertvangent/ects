<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use common\libraries\PropertiesTable;

use common\libraries\BreadcrumbTrail;

use common\libraries\Breadcrumb;

use application\discovery\module\group\implementation\bamaflex\Group;

use user\User;

use common\libraries\Toolbar;

use common\libraries\Display;

use user\UserDataManager;

use common\libraries\Request;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\group_user\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\group_user\Module
{
    const PARAM_SOURCE = 'source';
    const PARAM_TYPE = 'type';

    function get_group_user_parameters()
    {
        return self :: get_module_parameters();
    }

    static function get_module_parameters()
    {
        $group_class_id = Request :: get(self :: PARAM_GROUP_CLASS_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $type = Request :: get(self :: PARAM_TYPE);
        $parameter = new Parameters();
        
        if ($group_class_id)
        {
            $parameter->set_group_class_id($group_class_id);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        if ($type)
        {
            $parameter->set_type($type);
        }
        return $parameter;
    
    }

    function get_row($group_user)
    {
        $row = array();
        $row[] = $group_user->get_last_name();
        $row[] = $group_user->get_first_name();
        
        $toolbar = new Toolbar();
        
        $user = UserDataManager :: get_instance()->retrieve_user_by_official_code($group_user->get_person_id());
        if ($user instanceof User)
        {
            $profile_link = $this->get_module_link('application\discovery\module\profile\implementation\bamaflex', $user->get_id());
            if ($profile_link)
            {
                $toolbar->add_item($profile_link);
            }
            
            $career_link = $this->get_module_link('application\discovery\module\career\implementation\bamaflex', $user->get_id());
            if ($career_link)
            {
                $toolbar->add_item($career_link);
            }
        }
        $row[] = $toolbar->as_html();
        
        return $row;
    }

    function get_group_user_table()
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
        
        if ($this->get_group_user_parameters()->get_type() == Group :: TYPE_CLASS)
        {
            $parameters = $this->get_group_user_parameters();
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
            
            $tabs->add_tab(new DynamicContentTab(0, Translation :: get('Enrolled'), Theme :: get_image_path() . 'type/0.png', implode("\n", $html)));
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
            
            $tabs->add_tab(new DynamicContentTab(1, Translation :: get('Struck'), Theme :: get_image_path() . 'type/1.png', implode("\n", $html)));
        }
        
        return $tabs->render();
    }

    function get_table($data)
    {
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('FirstName'), false);
        $table->set_header(1, Translation :: get('LastName'), false);
        $table->set_header(2, '', false);
        
        return $table->as_html();
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\group_user.Module::render()
     */
    function render()
    {
        $html = array();
        $html[] = $this->get_group_properties_table();
        if (count($this->get_group_user()) > 0)
        {
            $html[] = $this->get_group_user_table();
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }

    function get_group_properties_table()
    {
        $group = DataManager :: get_instance($this->get_module_instance())->retrieve_group($this->get_group_parameters());

        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $group->get_year();
        $properties[Translation :: get('Code')] = $group->get_code();
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $group->get_description()));
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode("\n", $html);
    }

    static function get_group_parameters()
    {
        $group_class_id = Request :: get(self :: PARAM_GROUP_CLASS_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $type = Request :: get(self :: PARAM_TYPE);
        
        $parameter = new \application\discovery\module\group_user\implementation\bamaflex\Parameters();
        $parameter->set_group_class_id($group_class_id);
        $parameter->set_type($type);
        
        if ($source)
        {
            $parameter->set_source($source);
        }
        
        return $parameter;
    }
}
?>