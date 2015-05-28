<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseSections;

use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSection;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSetting;
use Ehb\Application\Avilarts\Storage\DataClass\CourseTool;
use Ehb\Application\Avilarts\Storage\DataClass\CourseToolRelCourseSection;
use Ehb\Application\Avilarts\Tool\Implementation\CourseSections\Storage\DataManager;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * $Id: course_section_tool_selector_form.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.course_sections
 */
class CourseSectionToolSelectorForm extends FormValidator
{

    private $course_section;

    public function __construct($course_section, $action)
    {
        parent :: __construct('course_sections', 'post', $action);
        
        $this->course_section = $course_section;
        $this->build_basic_form();
        $this->setDefaults();
    }

    public function build_basic_form()
    {
        // $sel = &
        $this->addElement(
            'advmultiselect', 
            'tools', 
            Translation :: get('SelectTools'), 
            $this->get_tools(), 
            array('style' => 'width:200px;'));
        // $this->addElement('submit', 'course_sections', 'OK');
        $buttons[] = $this->createElement(
            'style_submit_button', 
            'submit', 
            Translation :: get('Save', null, Utilities :: COMMON_LIBRARIES), 
            array('class' => 'positive'));
        $buttons[] = $this->createElement(
            'style_reset_button', 
            'reset', 
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), 
            array('class' => 'normal empty'));
        
        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Retrieve the tools the user can select (active and type tool)
     * 
     * @return array
     */
    public function get_tools()
    {
        
        // retrieve the tools
        $condition = new EqualityCondition(
            new PropertyConditionVariable(CourseTool :: class_name(), CourseTool :: PROPERTY_SECTION_TYPE), 
            new StaticConditionVariable(CourseSection :: TYPE_TOOL));
        
        $tools = DataManager :: retrieves(CourseTool :: class_name(), $condition);
        
        $active_tools = array();
        
        while ($tool = $tools->next_result())
        {
            $course_settings_controller = CourseSettingsController :: get_instance();
            
            if ($course_settings_controller->get_course_setting(
                Request :: get('course'), 
                CourseSetting :: COURSE_SETTING_TOOL_ACTIVE, 
                $tool->get_id()))
            {
                $active_tools[$tool->get_id()] = $tool->get_name();
            }
        }
        
        return $active_tools;
    }

    /**
     * Retrieve the tools already registered
     * 
     * @return type
     */
    public function get_registered_tools()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                CourseToolRelCourseSection :: class_name(), 
                CourseToolRelCourseSection :: PROPERTY_SECTION_ID), 
            new StaticConditionVariable($this->course_section->get_id()));
        
        return $registered_tools_resultset = DataManager :: retrieves(
            CourseToolRelCourseSection :: class_name(), 
            $condition);
    }

    public function update_course_modules()
    {
        // $course_section = $this->course_section;
        $values = $this->exportValues();
        $selected_tools = $values['tools'];
        
        // retrieve the sections for this course
        $condition = new EqualityCondition(
            new PropertyConditionVariable(CourseSection :: class_name(), CourseSection :: PROPERTY_COURSE_ID), 
            new StaticConditionVariable(Request :: get('course')));
        $course_sections = \Chamilo\Application\Weblcms\Storage\DataManager :: retrieves(
            CourseSection :: class_name(), 
            $condition);
        
        $course_section_ids = array();
        while ($course_section = $course_sections->next_result())
        {
            $course_section_ids[] = $course_section->get_id();
        }
        
        $registered_tools = $this->get_registered_tools();
        while ($registered_tool = $registered_tools->next_result())
        {
            if (in_array($registered_tool->get_tool_id(), $selected_tools))
            {
                // remove from selected tools because it's already assigned to
                // this course section
                $selected_tools = array_diff($selected_tools, array($registered_tool->get_tool_id()));
            }
            else
            {
                // remove the registerd tool from the course section (as it is
                // no longer selected)
                $registered_tool->delete();
            }
        }
        
        // add the remaining selected tools to the course section
        foreach ($selected_tools as $selected_tool_id)
        {
            // retrieve the relation if it exists for this tool (in another
            // section), so we can update it to the new tool
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    CourseToolRelCourseSection :: class_name(), 
                    CourseToolRelCourseSection :: PROPERTY_TOOL_ID), 
                new StaticConditionVariable($selected_tool_id));
            $conditions[] = new InCondition(
                new PropertyConditionVariable(
                    CourseToolRelCourseSection :: class_name(), 
                    CourseToolRelCourseSection :: PROPERTY_SECTION_ID), 
                $course_section_ids);
            $condition = new AndCondition($conditions);
            
            $course_tool_rel_course_sections = DataManager :: retrieves(
                CourseToolRelCourseSection :: class_name(), 
                $condition);
            
            if ($course_tool_rel_course_sections->size() > 0)
            {
                $course_tool_rel_course_section = $course_tool_rel_course_sections->next_result();
                $course_tool_rel_course_section->set_section_id($this->course_section->get_id());
                if (! $course_tool_rel_course_section->update())
                {
                    return false;
                }
            }
            else
            {
                $course_tool_rel_course_section = new CourseToolRelCourseSection();
                $course_tool_rel_course_section->set_tool_id($selected_tool_id);
                $course_tool_rel_course_section->set_section_id($this->course_section->get_id());
                if (! $course_tool_rel_course_section->create())
                {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Sets default values.
     * 
     * @param $defaults array Default values for this form's parameters.
     */
    public function setDefaults($defaults = array())
    {
        $registered_tools = $this->get_registered_tools();
        $registered_tools_array = array();
        while ($registered_tool = $registered_tools->next_result())
        {
            $registered_tools_array[] = $registered_tool->get_tool_id();
        }
        $defaults['tools'] = $registered_tools_array;
        
        parent :: setDefaults($defaults);
    }
}
