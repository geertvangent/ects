<?php
namespace Ehb\Application\Discovery\Module\GroupUser\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Group;
use Ehb\Application\Discovery\Module\GroupUser\DataManager;
use Ehb\Application\Discovery\Module\GroupUser\Implementation\Bamaflex\Module;
use Ehb\Application\Discovery\Module\GroupUser\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\GroupUser\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    /*
     * (non-PHPdoc) @see application\discovery\module\group_user.Module::render()
     */
    public function render()
    {
        if (! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $html = array();
        $html[] = $this->get_group_properties_table();
        if (count($this->get_group_user()) > 0)
        {
            $html[] = $this->get_group_user_table();
            
            \Ehb\Application\Discovery\Rendition\View\Html\HtmlDefaultRendition::add_export_action($this);
        }
        else
        {
            $html[] = Display::normal_message(Translation::get('NoData'), true);
        }
        return implode(PHP_EOL, $html);
    }

    public function get_group_properties_table()
    {
        $group = DataManager::getInstance($this->get_module_instance())->retrieve_group(Module::get_group_parameters());
        
        $properties = array();
        $properties[Translation::get('Year')] = $group->get_year();
        $properties[Translation::get('Code')] = $group->get_code();
        
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $group->get_description()));
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
        
        if ($this->get_module_parameters()->get_type() == Group::TYPE_CLASS)
        {
            $parameters = $this->get_module_parameters();
            $parameters->set_type(Group::TYPE_CLASS_COURSE);
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
                $html[] = '<br/><h3>' . Translation::get('ClassCourse') . '</h3>';
                $html[] = $this->get_table($course_data);
            }
            
            $tabs->add_tab(
                new DynamicContentTab(
                    0, 
                    Translation::get('Enrolled'), 
                    Theme::getInstance()->getImagesPath() . 'Type/0.png', 
                    implode(PHP_EOL, $html)));
        }
        
        if (count($data_struck) > 0 || count($course_data_struck) > 0)
        {
            $html = array();
            $html[] = $this->get_table($data_struck);
            if (count($course_data_struck) > 0)
            {
                $html[] = '<br/><h3>' . Translation::get('ClassCourse') . '</h3>';
                $html[] = $this->get_table($course_data_struck);
            }
            
            $tabs->add_tab(
                new DynamicContentTab(
                    1, 
                    Translation::get('Struck'), 
                    Theme::getInstance()->getImagesPath() . 'Type/1.png', 
                    implode(PHP_EOL, $html)));
        }
        
        return $tabs->render();
    }

    public function get_row($group_user)
    {
        $row = array();
        $row[] = $group_user->get_last_name();
        $row[] = $group_user->get_first_name();
        
        $toolbar = new Toolbar();
        
        $user = \Chamilo\Core\User\Storage\DataManager::retrieve_user_by_official_code($group_user->get_person_id());
        if ($user instanceof \Chamilo\Core\User\Storage\DataClass\User)
        {
            $profile_link = $this->get_module_link(
                'Ehb\Application\Discovery\Module\profile\Implementation\Bamaflex', 
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
        
        $table->setColumnHeader(0, Translation::get('FirstName'), false);
        $table->setColumnHeader(1, Translation::get('LastName'), false);
        $table->setColumnHeader(2, '', false);
        
        return $table->as_html();
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }
}
