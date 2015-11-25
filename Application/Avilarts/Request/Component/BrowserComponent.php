<?php
namespace Ehb\Application\Avilarts\Request\Component;

use Ehb\Application\Avilarts\Request\Manager;
use Ehb\Application\Avilarts\Request\Storage\DataClass\Request;
use Ehb\Application\Avilarts\Request\Storage\DataManager;
use Ehb\Application\Avilarts\Request\Table\Request\RequestTable;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    private $table_type;

    function run()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_USER_ID),
            new StaticConditionVariable($this->get_user_id()));
        $user_requests = DataManager :: count(Request :: class_name(), new DataClassCountParameters($condition));

        $tabs = new DynamicTabsRenderer('request');

        if ($user_requests > 0 ||
             \Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->request_is_allowed())
        {

            if ($user_requests > 0)
            {
                $this->table_type = RequestTable :: TYPE_PERSONAL;
                $table = new RequestTable($this);
                $tabs->add_tab(
                    new DynamicContentTab(
                        'personal_request',
                        Translation :: get('YourRequests'),
                        Theme :: getInstance()->getImagePath(
                            'Ehb\Application\Avilarts\Request',
                            'Tab/PersonalRequest'),
                        $table->as_html()));
            }

            if (\Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->request_is_allowed())
            {
                $target_users = \Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->get_target_users(
                    $this->get_user());

                if (count($target_users) > 0)
                {
                    $target_condition = new InCondition(
                        new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_USER_ID),
                        $target_users);
                }
                else
                {
                    $target_condition = new EqualityCondition(
                        new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_USER_ID),
                        new StaticConditionVariable(- 1));
                }

                $conditions = array();
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_DECISION),
                    new StaticConditionVariable(Request :: DECISION_PENDING));
                if (! $this->get_user()->is_platform_admin())
                {
                    $conditions[] = $target_condition;
                }
                $condition = new AndCondition($conditions);

                if (DataManager :: count(Request :: class_name(), $condition) > 0)
                {
                    $this->table_type = RequestTable :: TYPE_PENDING;
                    $table = new RequestTable($this);
                    $tabs->add_tab(
                        new DynamicContentTab(
                            RequestTable :: TYPE_PENDING,
                            Translation :: get('PendingRequests'),
                            Theme :: getInstance()->getImagePath(
                                'Ehb\Application\Avilarts\Request',
                                'Decision/22/' . Request :: DECISION_PENDING),
                            $table->as_html()));
                }

                $conditions = array();
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_DECISION),
                    new StaticConditionVariable(Request :: DECISION_GRANTED));
                if (! $this->get_user()->is_platform_admin())
                {
                    $conditions[] = $target_condition;
                }
                $condition = new AndCondition($conditions);

                if (DataManager :: count(Request :: class_name(), $condition) > 0)
                {
                    $this->table_type = RequestTable :: TYPE_GRANTED;
                    $table = new RequestTable($this);
                    $tabs->add_tab(
                        new DynamicContentTab(
                            RequestTable :: TYPE_GRANTED,
                            Translation :: get('GrantedRequests'),
                            Theme :: getInstance()->getImagePath(
                                'Ehb\Application\Avilarts\Request',
                                'Decision/22/' . Request :: DECISION_GRANTED),
                            $table->as_html()));
                }

                $conditions = array();
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_DECISION),
                    new StaticConditionVariable(Request :: DECISION_DENIED));
                if (! $this->get_user()->is_platform_admin())
                {
                    $conditions[] = $target_condition;
                }
                $condition = new AndCondition($conditions);

                if (DataManager :: count(Request :: class_name(), $condition) > 0)
                {
                    $this->table_type = RequestTable :: TYPE_DENIED;
                    $table = new RequestTable($this);
                    $tabs->add_tab(
                        new DynamicContentTab(
                            RequestTable :: TYPE_DENIED,
                            Translation :: get('DeniedRequests'),
                            Theme :: getInstance()->getImagePath(
                                'Ehb\Application\Avilarts\Request',
                                'Decision/22/' . Request :: DECISION_DENIED),
                            $table->as_html()));
                }
            }
        }

        if ($user_requests > 0 || (\Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->request_is_allowed() &&
             $tabs->size() > 0) || $this->get_user()->is_platform_admin())
        {
            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->get_action_bar()->as_html();
            $html[] = $tabs->render();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
        else
        {
            $this->redirect(
                Translation :: get('NoRequestsFormDirectly'),
                null,
                array(self :: PARAM_ACTION => self :: ACTION_CREATE));
        }
    }

    /**
     *
     * @see @see common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_table_condition($object_table_class_name)
    {
        $conditions = array();

        switch ($this->table_type)
        {
            case RequestTable :: TYPE_PENDING :
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_DECISION),
                    new StaticConditionVariable(Request :: DECISION_PENDING));
                break;
            case RequestTable :: TYPE_PERSONAL :
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_USER_ID),
                    new StaticConditionVariable($this->get_user_id()));
                break;
            case RequestTable :: TYPE_GRANTED :
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_DECISION),
                    new StaticConditionVariable(Request :: DECISION_GRANTED));
                break;
            case RequestTable :: TYPE_DENIED :
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_DECISION),
                    new StaticConditionVariable(Request :: DECISION_DENIED));
                break;
        }

        if (! $this->get_user()->is_platform_admin() &&
             \Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->request_is_allowed() &&
             $this->table_type != RequestTable :: TYPE_PERSONAL)
        {
            $target_users = \Ehb\Application\Avilarts\Request\Rights\Rights :: get_instance()->get_target_users(
                $this->get_user());

            if (count($target_users) > 0)
            {
                $conditions[] = new InCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_USER_ID),
                    $target_users);
            }
            else
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Request :: class_name(), Request :: PROPERTY_USER_ID),
                    new StaticConditionVariable(- 1));
            }
        }

        return new AndCondition($conditions);
    }

    function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);

        if ($this->request_allowed())
        {
            $action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('RequestCourse'),
                    Theme :: getInstance()->getImagePath('Ehb\Application\Avilarts\Request', 'Action/Request'),
                    $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));
        }

        if ($this->get_user()->is_platform_admin())
        {
            $action_bar->add_tool_action(
                new ToolbarItem(
                    Translation :: get('ConfigureManagementRights'),
                    Theme :: getInstance()->getImagePath('Ehb\Application\Avilarts\Request', 'Action/Rights'),
                    $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_RIGHTS))));
        }

        return $action_bar;
    }

    function get_table_type()
    {
        return $this->table_type;
    }
}
?>