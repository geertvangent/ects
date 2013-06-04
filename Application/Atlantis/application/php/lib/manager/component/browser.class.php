<?php
namespace application\atlantis\application;

use common\libraries\Breadcrumb;
use application\atlantis\SessionBreadcrumbs;
use common\libraries\DelegateComponent;
use common\libraries\OrCondition;
use common\libraries\PatternMatchCondition;
use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\ActionBarRenderer;
use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport, DelegateComponent
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();

        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            $search_conditions[] = new PatternMatchCondition(Application :: PROPERTY_NAME, '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(Application :: PROPERTY_DESCRIPTION, '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(Application :: PROPERTY_URL, '*' . $query . '*');
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

            if ($this->get_user()->is_platform_admin())
            {
                $this->action_bar->add_common_action(
                        new ToolbarItem(Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
                                Theme :: get_common_image_path() . 'action_create.png',
                                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));
            }
            $this->action_bar->set_search_url($this->get_url());
        }
        return $this->action_bar;
    }
}
