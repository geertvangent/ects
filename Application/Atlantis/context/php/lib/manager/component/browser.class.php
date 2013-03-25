<?php
namespace application\atlantis\context;

use common\libraries\Request;
use common\libraries\Breadcrumb;
use application\atlantis\SessionBreadcrumbs;
use common\libraries\OrCondition;
use common\libraries\PatternMatchCondition;
use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\ActionBarRenderer;
use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();
        
        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            // $search_conditions[] = new PatternMatchCondition(\application\atlantis\role\entity\RoleEntity ::
            // PROPERTY_ID,
            // '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                    \application\atlantis\context\Context :: PROPERTY_CONTEXT_NAME, '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(\application\atlantis\role\Role :: PROPERTY_NAME, 
                    '*' . $query . '*');
            return new OrCondition($search_conditions);
        }
        else
        {
            return null;
        }
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
        
        $table = new ContextTable($this);
        $this->display_header();
        $this->get_action_bar()->as_html();
        $menu = new Menu($this->get_context());
        echo $menu->render_as_tree();
        echo $table->as_html();
        $this->display_footer();
    }

    public function get_action_bar()
    {
        if (! isset($this->action_bar))
        {
            $this->action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
            
            $this->action_bar->set_search_url($this->get_url());
        }
        return $this->action_bar;
    }
}
