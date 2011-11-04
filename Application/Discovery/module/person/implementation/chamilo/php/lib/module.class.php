<?php
namespace application\discovery\module\person\implementation\chamilo;

use application\discovery\Parameters;

use application\discovery\DiscoveryManager;

use common\libraries\Theme;

use common\libraries\Utilities;

use common\libraries\ToolbarItem;

use common\libraries\ActionBarRenderer;

use common\libraries\OrCondition;

use user\User;

use common\libraries\PatternMatchCondition;

use common\libraries\ActionBarSearchForm;

use common\libraries\Translation;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\person\Module
{
    private $action_bar;

    function render()
    {
        $this->action_bar = $this->get_action_bar();
        
        $parameters = $this->get_application()->get_parameters(true);
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $parameters[DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        
        $table = new UserBrowserTable($this, $parameters, $this->get_condition());
        $html[] = $this->action_bar->as_html() . '<br />';
        $html[] = $table->as_html();
        return implode("\n", $html);
    }

    function get_condition()
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

    function get_action_bar()
    {
        $parameters = $this->get_application()->get_parameters(true);
        $parameters[DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_application()->get_url($parameters));
        
        $action_bar->add_common_action(new ToolbarItem(Translation :: get('Show', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_browser.png', $this->get_application()->get_url(), ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }

    static function get_module_parameters()
    {
        return new Parameters();
    }

}
?>