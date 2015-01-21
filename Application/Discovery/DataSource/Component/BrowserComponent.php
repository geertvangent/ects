<?php
namespace Chamilo\Application\Discovery\DataSource\Component;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBarSearchForm;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Application\Discovery\DataSource\DataClass\Instance;
use Chamilo\Application\Discovery\DataSource\Table\Instance\InstanceTable;
use Chamilo\Application\Discovery\DataSource\Manager;

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

        $this->display_header();
        echo $this->action_bar->as_html();
        echo $table->as_html();
        $this->display_footer();
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
                Theme :: get_common_image_path() . 'action_create.png',
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE_INSTANCE)),
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }
}
