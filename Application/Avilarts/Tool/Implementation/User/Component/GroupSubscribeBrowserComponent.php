<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component;

use Ehb\Application\Avilarts\Tool\Implementation\User\Component\UnsubscribedGroup\UnsubscribedGroupTable;
use Ehb\Application\Avilarts\Tool\Implementation\User\PlatformgroupMenuRenderer;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;

/**
 * $Id: user_group_subscribe_browser.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.user.component
 */
class GroupSubscribeBrowserComponent extends Manager implements TableSupport
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    private $group;

    private $root_group;

    private $subscribed_groups;

    public function run()
    {
        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT))
        {
            throw new NotAllowedException();
        }
        
        $this->subscribed_groups = $this->get_subscribed_platformgroup_ids($this->get_course_id());
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $this->buttonToolbarRenderer->render();
        $html[] = $this->get_group_menu();
        $html[] = $this->get_group_subscribe_html();
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function get_group_subscribe_html()
    {
        $table = new UnsubscribedGroupTable($this, $this->get_parameters(), $this->get_condition());
        
        $html = array();
        $html[] = '<div style="width: 70%; float: right;">';
        $html[] = $table->as_html();
        $html[] = '</div>';
        
        return implode($html, "\n");
    }

    public function get_group_menu()
    {
        $tree = new PlatformgroupMenuRenderer($this, array($this->get_root_group()->get_id()));
        
        $html = array();
        $html[] = '<div style="overflow: auto; width: 29%; float: left;">';
        $html[] = $tree->render_as_tree();
        $html[] = '</div>';
        
        return implode($html, "\n");
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar($this->get_url());
            $commonActions = new ButtonGroup();
            
            $commonActions->addButton(
                new Button(
                    Translation :: get('ViewUsers'), 
                    Theme :: getInstance()->getCommonImagePath('Action/Browser'), 
                    $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_UNSUBSCRIBE_BROWSER)), 
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_GROUP);
            
            if (! $this->group)
            {
                $this->group = $this->get_root_group()->get_id();
            }
        }
        
        return $this->group;
    }

    public function get_root_group()
    {
        if (! $this->root_group)
        {
            $group = \Chamilo\Core\Group\Storage\DataManager :: retrieve(
                Group :: class_name(), 
                new DataClassRetrieveParameters(
                    new EqualityCondition(
                        new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID), 
                        new StaticConditionVariable(0))));
            $this->root_group = $group;
        }
        
        return $this->root_group;
    }

    public function get_condition()
    {
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID), 
            new StaticConditionVariable($this->get_group()));
        
        // filter already subscribed groups
        if ($this->subscribed_groups)
        {
            $conditions[] = new NotCondition(
                new InCondition(
                    new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_ID), 
                    $this->subscribed_groups));
        }
        
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        if (isset($query) && $query != '')
        {
            $conditions2[] = new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_NAME), 
                '*' . $query . '*');
            $conditions2[] = new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
            $conditions[] = new OrCondition($conditions2);
        }
        
        return new AndCondition($conditions);
    }

    public function get_additional_parameters()
    {
        return array(self :: PARAM_TAB, \Ehb\Application\Avilarts\Manager :: PARAM_GROUP);
    }

    public function get_table_condition($table_class_name)
    {
        return $this->get_condition();
    }
}
