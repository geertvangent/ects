<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Component;

use Ehb\Application\Avilarts\CourseSettingsConnector;
use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Rights\WeblcmsRights;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\CourseGroupMenu;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataManager;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Table\CourseGroup\CourseGroupTable;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Table\CourseGroup\CourseGroupTableDataProvider;
use Chamilo\Core\Repository\ContentObject\Introduction\Storage\DataClass\Introduction;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ConditionProperty;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\SubselectCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * $Id: course_group_browser.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.course_group.component
 */
class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    private $action_bar;

    private $introduction_text;

    public function run()
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($this->get_course_id()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_TOOL),
            new StaticConditionVariable('course_group'));

        $subselect_condition = new EqualityCondition(
            new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_TYPE),
            new StaticConditionVariable(Introduction :: class_name()));

        $conditions[] = new SubselectCondition(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_CONTENT_OBJECT_ID),
            new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_ID),
            ContentObject :: get_table_name(),
            $subselect_condition);

        $condition = new AndCondition($conditions);

        $this->introduction_text = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve(
            ContentObjectPublication :: class_name(),
            $condition);

        $this->action_bar = $this->get_action_bar();

        $html = array();

        $html[] = $this->render_header();

        $intro_text_allowed = CourseSettingsController :: get_instance()->get_course_setting(
            $this->get_course_id(),
            CourseSettingsConnector :: ALLOW_INTRODUCTION_TEXT);

        if ($intro_text_allowed)
        {
            $html[] = $this->display_introduction_text($this->introduction_text);
        }

        $html[] = $this->action_bar->as_html();
        $html[] = $this->get_menu_html();
        $html[] = $this->get_table_html();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function get_menu_html()
    {
        $group_menu = new CourseGroupMenu($this->get_course(), $this->get_group_id());

        $html = array();

        $html[] = '<div style="float: left; width: 18%; overflow: auto; height: 500px;">';
        $html[] = $group_menu->render_as_tree();
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    public function get_table_html()
    {
        $parameters = $this->get_parameters(true);

        $parameters[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = self :: ACTION_BROWSE;

        $course_group_table = new CourseGroupTable($this, new CourseGroupTableDataProvider($this));

        $html = array();
        $html[] = '<div style="float: right; width: 80%;">';
        $html[] = $course_group_table->as_html();
        $html[] = '</div>';
        $html[] = '<div class="clear"></div>';

        return implode($html, "\n");
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_url());

        $param_show_all[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = self :: ACTION_VIEW_GROUPS;
        $param_show_all[\Ehb\Application\Avilarts\Manager :: PARAM_COURSE] = Request :: get(
            \Ehb\Application\Avilarts\Manager :: PARAM_COURSE);
        $param_show_all[\Ehb\Application\Avilarts\Manager :: PARAM_COURSE_GROUP] = null;

        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('ShowAll', null, Utilities :: COMMON_LIBRARIES),
                Theme :: getInstance()->getCommonImagePath('Action/Browser'),
                $this->get_url($param_show_all),
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));

        $param_add_course_group[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = self :: ACTION_ADD_COURSE_GROUP;
        $param_add_course_group[\Ehb\Application\Avilarts\Manager :: PARAM_COURSE_GROUP] = $this->get_group_id();

        $param_subscriptions_overview[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = self :: ACTION_SUBSCRIPTIONS_OVERVIEW;
        $param_subscriptions_overview[\Ehb\Application\Avilarts\Manager :: PARAM_COURSE_GROUP] = $this->get_group_id();

        if ($this->is_allowed(WeblcmsRights :: ADD_RIGHT))
        {
            $action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('Create'),
                    Theme :: getInstance()->getCommonImagePath('Action/Create'),
                    $this->get_url($param_add_course_group),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }

        if (! $this->introduction_text && $this->is_allowed(WeblcmsRights :: EDIT_RIGHT))
        {
            $action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('PublishIntroductionText', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: getInstance()->getCommonImagePath('Action/Introduce'),
                    $this->get_url(
                        array(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_PUBLISH_INTRODUCTION)),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }

        if ($this->is_allowed(WeblcmsRights :: EDIT_RIGHT))
        {
            $action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('ViewSubscriptions'),
                    Theme :: getInstance()->getCommonImagePath('Action/Browser'),
                    $this->get_url(
                        $param_subscriptions_overview,
                        array(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE_GROUP)),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }

        return $action_bar;
    }

    public function get_condition()
    {
        $conditions = array();
        $properties = array();
        $properties[] = new ConditionProperty(
            new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_NAME));
        $properties[] = new ConditionProperty(
            new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_DESCRIPTION));
        $query_condition = $this->action_bar->get_conditions($properties);

        $root_course_group = DataManager :: retrieve_course_group_root($this->get_course()->get_id());

        $course_group_id = $this->get_group_id();

        if (! $course_group_id || ($root_course_group->get_id() == $course_group_id))
        {
            $root_course_group_id = $root_course_group->get_id();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_PARENT_ID),
                new StaticConditionVariable($root_course_group_id));
        }
        else
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_PARENT_ID),
                new StaticConditionVariable($course_group_id));
        }

        if ($query_condition)
        {
            $conditions[] = $query_condition;
        }

        if (count($conditions) > 0)
        {
            return new AndCondition($conditions);
        }
    }

    public function get_group_id()
    {
        return Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE_GROUP);
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        return $this->get_condition();
    }
}
