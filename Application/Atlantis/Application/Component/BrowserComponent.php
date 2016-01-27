<?php
namespace Ehb\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ActionBar\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Manager;
use Ehb\Application\Atlantis\Application\Storage\DataClass\Application;
use Ehb\Application\Atlantis\Application\Table\Application\ApplicationTable;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();

        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Application :: class_name(), Application :: PROPERTY_NAME),
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Application :: class_name(), Application :: PROPERTY_DESCRIPTION),
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Application :: class_name(), Application :: PROPERTY_URL),
                '*' . $query . '*');
            return new OrCondition($search_conditions);
        }
        else
        {
            return null;
        }
    }

    public function run()
    {
        SessionBreadcrumbs :: add(new Breadcrumb($this->get_url(), Translation :: get('TypeName')));

        $this->action_bar = $this->get_action_bar();
        $table = new ApplicationTable($this);

        $html = array();

        $html[] = $this->render_header();
        $html[] = $this->action_bar->as_html();
        $html[] = $table->as_html();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function get_action_bar()
    {
        if (! isset($this->action_bar))
        {
            $this->action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);

            if ($this->get_user()->is_platform_admin())
            {
                $this->action_bar->add_common_action(
                    new ToolbarItem(
                        Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: getInstance()->getCommonImagePath('Action/Create'),
                        $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));
            }
            $this->action_bar->set_search_url($this->get_url());
        }
        return $this->action_bar;
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
