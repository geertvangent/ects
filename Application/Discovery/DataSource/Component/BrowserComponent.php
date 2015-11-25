<?php
namespace Ehb\Application\Discovery\DataSource\Component;

use Ehb\Application\Discovery\DataSource\Storage\DataClass\Instance;
use Ehb\Application\Discovery\DataSource\Manager;
use Ehb\Application\Discovery\DataSource\Table\Instance\InstanceTable;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBarSearchForm;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;

class BrowserComponent extends Manager implements TableSupport
{

    private $action_bar;

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }

        $this->action_bar = $this->get_action_bar();
        $parameters = $this->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $table = new InstanceTable($this);

        $html = array();

        $html[] = $this->render_header();
        $html[] = $this->action_bar->as_html();
        $html[] = $table->as_html();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function get_table_condition($table_class_name)
    {
        $query = $this->action_bar->get_query();

        if (isset($query) && $query != '')
        {
            $conditions = array();
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_NAME),
                '*' . $query . '*');
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION),
                '*' . $query . '*');

            $condition = new AndCondition($conditions);
            return $condition;
        }
        else
        {
            return null;
        }
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('AddInstance'),
                Theme :: getInstance()->getCommonImagePath('Action/Create'),
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE_INSTANCE)),
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }
}
