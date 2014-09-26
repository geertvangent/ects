<?php
namespace application\atlantis\role;

use application\atlantis\SessionBreadcrumbs;
use libraries\Breadcrumb;
use libraries\DelegateComponent;
use libraries\OrCondition;
use libraries\PatternMatchCondition;
use libraries\Theme;
use libraries\Utilities;
use libraries\Translation;
use libraries\ToolbarItem;
use libraries\ActionBarRenderer;
use libraries\TableSupport;
use libraries\PropertyConditionVariable;

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
                new PropertyConditionVariable(Role :: class_name(), Role :: PROPERTY_NAME),
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Role :: class_name(), Role :: PROPERTY_DESCRIPTION),
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
        echo $this->action_bar->as_html();
        $table = new RoleTable($this);
        echo $table->as_html();
        $this->display_footer();
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
                        Theme :: get_common_image_path() . 'action_create.png',
                        $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));
            }
            $this->action_bar->set_search_url($this->get_url());
        }
        return $this->action_bar;
    }
	/* (non-PHPdoc)
     * @see \libraries\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub

    }

}
