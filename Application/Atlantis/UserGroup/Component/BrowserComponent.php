<?php
namespace Chamilo\Application\Atlantis\UserGroup\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Application\Atlantis\UserGroup\Manager;
use Chamilo\Application\Atlantis\UserGroup\Table\Application\ApplicationTable;

class BrowserComponent extends Manager implements TableSupport
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();

        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_NAME),
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_DESCRIPTION),
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_URL),
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

        $this->display_header();

        $this->action_bar = $this->get_action_bar();
        echo ($this->action_bar->as_html());
        $table = new ApplicationTable($this);
        echo ($table->as_html());
        $this->display_footer();
    }

    public function get_action_bar()
    {
        if (! isset($this->action_bar))
        {
            $this->action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
            $this->action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_create.png',
                    $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));

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
