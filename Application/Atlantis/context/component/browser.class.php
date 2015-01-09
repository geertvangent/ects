<?php
namespace application\atlantis\context;

use libraries\storage\AndCondition;
use libraries\storage\EqualityCondition;
use libraries\platform\Request;
use libraries\format\Breadcrumb;
use application\atlantis\SessionBreadcrumbs;
use libraries\storage\OrCondition;
use libraries\storage\PatternMatchCondition;
use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\format\structure\ToolbarItem;
use libraries\format\ActionBarRenderer;
use libraries\format\TableSupport;
use libraries\storage\PropertyConditionVariable;
use libraries\storage\StaticConditionVariable;
use core\group\Group;

class BrowserComponent extends Manager implements TableSupport
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();
        $conditions = array();

        if (isset($query) && $query != '')
        {
            $search_conditions = array();

            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_NAME),
                '*' . $query . '*');

            $conditions[] = new OrCondition($search_conditions);
        }
        if ($this->get_context() != 0)
        {
            $context = \core\group\DataManager :: retrieve_by_id(Group :: class_name(), (int) $this->get_context());
        }
        else
        {
            $context = new Group();
            $context->set_id(0);
            $context->set_name(Translation :: get('Root'));
        }

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
            new StaticConditionVariable($context->get_id()));

        return new AndCondition($conditions);
    }

    function get_context()
    {
        if (! $this->context)
        {
            $this->context = Request :: get(self :: PARAM_CONTEXT_ID);

            if (! $this->context)
            {
                $this->context = 0;
            }
        }

        return $this->context;
    }

    public function run()
    {
        SessionBreadcrumbs :: add(new Breadcrumb($this->get_url(), Translation :: get('TypeName')));

        $this->set_parameter(Manager :: PARAM_CONTEXT_ID, $this->get_context());
        $table = new ContextTable($this);
        $this->display_header();

        echo '<div style="float: left; width: 30%; overflow:auto;">';
        $menu = new Menu($this->get_context());
        echo $menu->render_as_tree();
        echo '</div>';
        echo '<div style="float: right; width: 69%;">';
        echo $this->get_action_bar()->as_html();
        echo $table->as_html();
        echo '</div>';
        $this->display_footer();
    }

    public function get_action_bar()
    {
        if (! isset($this->action_bar))
        {
            $this->action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
            $this->action_bar->add_common_action(
                new ToolbarItem(
                    Translation :: get('TypeName', null, '\application\atlantis\role\entity'),
                    Theme :: get_image_path('\application\atlantis\role\entity') . 'logo/16.png',
                    $this->get_url(
                        array(
                            \application\atlantis\Manager :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE,
                            \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY,
                            \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager :: ACTION_BROWSE,
                            Manager :: PARAM_CONTEXT_ID => $this->get_context())),
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        }
        return $this->action_bar;
    }
	/* (non-PHPdoc)
     * @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub

    }

}
