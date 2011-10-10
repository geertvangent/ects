<?php
namespace application\discovery;

use common\libraries\Theme;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Redirect;
use common\libraries\Request;

use application\discovery\module\profile\Profile;
use application\discovery\module\profile\implementation\bamaflex\SettingsConnector;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class DiscoveryManagerViewerComponent extends DiscoveryManager
{
    /**
     * @var int
     */
    private $user_id;

    function run()
    {
        $this->user_id = Request :: get(DiscoveryManager :: PARAM_USER_ID);
        $module_id = Request :: get(DiscoveryManager :: PARAM_MODULE_ID);

//        if ($this->user_id)
//        {
            $this->display_header();

            if (! $module_id)
            {
                $module_id = 1;
            }

            $current_module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance($module_id);
            $current_module = Module :: factory($this, $current_module_instance);

            $tabs = new DynamicVisualTabsRenderer('discovery', $current_module->render());
            $module_instances = DiscoveryDataManager :: get_instance()->retrieve_module_instances();

            while ($module_instance = $module_instances->next_result())
            {
                $selected = ($module_id == $module_instance->get_id() ? true : false);
                $link = $this->get_url(array(
                        DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id(),
                        DiscoveryManager :: PARAM_USER_ID => $this->user_id));
                $tabs->add_tab(new DynamicVisualTab($module_instance->get_id(), $module_instance->get_title(), Theme :: get_image_path($module_instance->get_type()) . 'logo/22.png', $link, $selected));
            }

            echo $tabs->render();

            echo '<div id="legend">';
            echo LegendTable::get_instance()->as_html();
            echo '</div>';

            $this->display_footer();
//        }
//        else
//        {
//            Redirect :: get_url(array(DiscoveryManager :: ACTION_BROWSE));
//        }
    }

    function get_user_id()
    {
        return $this->user_id;
    }
}
?>