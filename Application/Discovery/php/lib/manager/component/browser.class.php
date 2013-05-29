<?php
namespace application\discovery;

use user\User;
use common\libraries\OrCondition;
use common\libraries\PatternMatchCondition;
use common\libraries\ActionBarSearchForm;
use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\ActionBarRenderer;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class BrowserComponent extends Manager
{

    /**
     *
     * @var ActionBarRenderer
     */
    private $action_bar;

    public function run()
    {
        $this->action_bar = $this->get_action_bar();
        
        $parameters = $this->get_parameters(true);
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        
        $table = new UserBrowserTable($this, $parameters, $this->get_condition());
        
        $this->display_header();
        echo $this->action_bar->as_html() . '<br />';
        echo $table->as_html();
        $this->display_footer();
    }

    public function get_condition()
    {
        $query = $this->action_bar->get_query();
        
        if (isset($query) && $query != '')
        {
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_FIRSTNAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_LASTNAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_USERNAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            return null;
        }
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_url());
        
        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('Show', null, Utilities :: COMMON_LIBRARIES), 
                Theme :: get_common_image_path() . 'action_browser.png', 
                $this->get_url(), 
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }
}
