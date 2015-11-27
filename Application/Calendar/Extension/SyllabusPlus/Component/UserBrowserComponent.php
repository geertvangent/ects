<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBarSearchForm;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Table\User\UserTable;
use Chamilo\Libraries\Storage\Query\Condition\Condition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;

class UserBrowserComponent extends Manager implements DelegateComponent, TableSupport
{

    private $firstletter;

    private $menu_breadcrumbs;

    private $action_bar;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->checkAuthorization();

        $this->firstletter = Request :: get(self :: PARAM_FIRSTLETTER);

        $content = array();
        $content[] = $this->get_action_bar()->as_html() . '<br />';
        $content[] = $this->get_user_html();

        $tabs = $this->getTabs();
        $tabs->set_content(implode(PHP_EOL, $content));

        $html = array();

        $html[] = $this->render_header();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function get_user_html()
    {
        $table = new UserTable($this);
        $html = array();
        $html[] = '<div style="float: right; width: 100%;">';
        $html[] = $table->as_html();
        $html[] = '</div>';

        return implode($html, "\n");
    }

    public function get_parameters()
    {
        $parameters = parent :: get_parameters();
        if (isset($this->action_bar))
        {
            $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->get_action_bar()->get_query();
        }
        return $parameters;
    }

    /*
     * (non-PHPdoc) @see common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_table_condition($class_name)
    {
        // construct search properties
        $search_properties = array();
        $search_properties[] = new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_OFFICIAL_CODE);
        $search_properties[] = new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_FIRSTNAME);
        $search_properties[] = new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_LASTNAME);
        $search_properties[] = new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_USERNAME);
        $search_properties[] = new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_EMAIL);

        // get conditions
        $searchCondition = $this->get_action_bar()->get_conditions($search_properties);

        // Conditions for active user with officialcode and email
        $activeConditions = array();
        $activeConditions[] = new EqualityCondition(
            new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ACTIVE),
            new StaticConditionVariable(1));
        $activeConditions[] = new NotCondition(
            new EqualityCondition(
                new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_OFFICIAL_CODE),
                new StaticConditionVariable(NULL)));
        $activeConditions[] = new NotCondition(
            new PatternMatchCondition(
                new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_OFFICIAL_CODE),
                'EXT*'));
        $emailConditions = array();
        $emailConditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_EMAIL),
            '*@ehb.be');
        $emailConditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_EMAIL),
            '*@student.ehb.be');
        $activeConditions[] = new OrCondition($emailConditions);

        $activeCondition = new AndCondition($activeConditions);

        if ($searchCondition instanceof Condition)
        {
            $conditions = array();
            $conditions[] = $searchCondition;
            $conditions[] = $activeCondition;
            return new AndCondition($conditions);
        }
        else
        {
            return $activeCondition;
        }
    }

    public function get_action_bar()
    {
        if (! isset($this->action_bar))
        {
            $this->action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
            $this->action_bar->set_search_url($this->get_url(parent :: get_parameters()));
            $this->action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('Show', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: getInstance()->getCommonImagePath('Action/Browser'),
                    $this->get_url(),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }

        return $this->action_bar;
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('user_browser');
    }
}
