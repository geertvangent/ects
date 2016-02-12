<?php
namespace Ehb\Application\Avilarts\Course\Component;

use Ehb\Application\Avilarts\Course\Manager;
use Ehb\Application\Avilarts\Course\Storage\DataClass\Course;
use Ehb\Application\Avilarts\Course\Table\CourseTable\CourseTable;
use Ehb\Application\Avilarts\Menu\CourseCategoryMenu;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\ActionBar\ActionBarSearchForm;

/**
 * This class describes a browser for the courses
 * 
 * @package \application\Avilarts\course
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
class BrowseComponent extends Manager implements TableSupport
{
    /**
     * The category id
     */
    const PARAM_CATEGORY_ID = 'category_id';

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        if ($this->can_view_component())
        {
            $html = array();
            
            $html[] = $this->render_header();
            $html[] = $this->get_html();
            $html[] = $this->render_footer();
            
            return implode(PHP_EOL, $html);
        }
        else
        {
            throw new NotAllowedException();
        }
    }

    /**
     * Returns the condition for the table
     * 
     * @param string $object_table_class_name
     *
     * @return \libraries\storage\Condition
     */
    public function get_table_condition($object_table_class_name)
    {
        $conditions = array();
        
        $category_id = Request :: get(self :: PARAM_CATEGORY_ID);
        if ($category_id)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Course :: class_name(), Course :: PROPERTY_CATEGORY_ID), 
                new StaticConditionVariable($category_id));
        }
        
        $search_condition = $this->buttonToolbarRenderer->getConditions(
            array(Course :: PROPERTY_TITLE, Course :: PROPERTY_VISUAL_CODE));
        
        if ($search_condition)
        {
            $conditions[] = $search_condition;
        }
        
        if (count($conditions) > 0)
        {
            return new AndCondition($conditions);
        }
    }

    /**
     * **************************************************************************************************************
     * Helper Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the html for this component
     * 
     * @return String
     */
    protected function get_html()
    {
        $html = array();
        
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        
        $html[] = '<div style="clear: both;"></div>';
        $html[] = $this->buttonToolbarRenderer->render() . '<br />';
        
        $temp_replacement = '__CATEGORY_ID__';
        
        $url_format = $this->get_url(array(self :: PARAM_CATEGORY_ID => $temp_replacement));
        $url_format = str_replace($temp_replacement, '%s', $url_format);
        
        $category_menu = new CourseCategoryMenu(Request :: get(self :: PARAM_CATEGORY_ID), $url_format);
        
        $html[] = '<div style="float: left; padding-right: 20px; width: 18%; overflow: auto; height: 100%;">';
        $html[] = $category_menu->render_as_tree();
        $html[] = '</div>';
        
        $course_table = $this->get_course_table();
        
        $html[] = '<div style="float: right; width: 80%;">';
        $html[] = $course_table->as_html();
        $html[] = '</div>';
        
        $html[] = '<div style="clear: both;"></div>';
        $html[] = '</div>';
        $html[] = '</div>';
        
        return implode($html, "\n");
    }

    /**
     * Returns the course table for this component
     * 
     * @return CourseTable
     */
    protected function get_course_table()
    {
        return new CourseTable($this);
    }

    /**
     * Checkes whether or not the current user can view this component
     * 
     * @return boolean
     */
    protected function can_view_component()
    {
        return $this->get_user()->is_platform_admin();
    }

    /**
     * Creates and returns the action bar
     * 
     * @return ButtonToolbarRenderer
     */
    protected function getButtonToolbarRenderer()
    {
        $buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $buttonToolbar = $buttonToolbarRenderer->getToolBar();
        $commonActions = new ButtonGroup();
        
        $commonActions->addButton(
            new Button(
                Translation :: get('Add', null, Utilities :: COMMON_LIBRARIES), 
                Theme :: getInstance()->getCommonImagePath('Action/Add'), 
                $this->get_create_course_url(), 
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        
        $buttonToolbar->addButtonGroup($commonActions);
        
        return $buttonToolbarRenderer;
    }

    /**
     * Builds the basic actionbar for this component
     * 
     * @return ButtonToolBarRenderer
     */
    protected function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar($this->get_url());
            $commonActions = new ButtonGroup();
            
            $commonActions->addButton(
                new Button(
                    Translation :: get('ShowAll', null, Utilities :: COMMON_LIBRARIES), 
                    Theme :: getInstance()->getCommonImagePath('Action/Browser'), 
                    $this->get_url(), 
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    /**
     * **************************************************************************************************************
     * Getters & Setters *
     * **************************************************************************************************************
     */
    public function get_parameters()
    {
        $parameters = parent :: get_parameters();
        $parameters[self :: PARAM_CATEGORY_ID] = Request :: get(self :: PARAM_CATEGORY_ID);
        
        if (isset($this->buttonToolbarRenderer))
        {
            $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->getButtonToolbarRenderer()->getSearchForm()->getQuery();
        }
        return $parameters;
    }
}
