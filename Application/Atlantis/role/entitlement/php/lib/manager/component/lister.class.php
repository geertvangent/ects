<?php
namespace application\atlantis\role\entitlement;

use common\libraries\DynamicVisualTab;

use common\libraries\Request;
use common\libraries\DynamicVisualTabsRenderer;
use application\atlantis\role\Role;
use common\libraries\SimpleTable;
use common\libraries\PropertiesTable;
use application\atlantis\application\right\RightTable;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\EqualityCondition;
use common\libraries\Translation;
use common\libraries\DynamicContentTab;
use common\libraries\Utilities;
use common\libraries\DynamicTabsRenderer;
use common\libraries\NewObjectTableSupport;

class ListerComponent extends Manager implements NewObjectTableSupport
{

    function run()
    {
        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $role_id = Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID);
        $application_id = Request :: get(\application\atlantis\application\Manager :: PARAM_APPLICATION_ID);
        
        // for each application, a list of rights
        $applications = \application\atlantis\application\DataManager :: retrieves(\application\atlantis\application\Application :: class_name());
        if (! $application_id)
        {
            $application_id = $applications->next_result()->get_id();
            $applications->reset();
        }
        $tabs = new DynamicVisualTabsRenderer($renderer_name, $this->get_rights($application_id, $role_id));
        
        while ($application = $applications->next_result())
        {
            $link = $this->get_url(array(\application\atlantis\role\Manager :: PARAM_ROLE_ID => $role_id, 
                    \application\atlantis\application\Manager :: PARAM_APPLICATION_ID => $application_id));
            $tabs->add_tab(new DynamicVisualTab($application->get_id, Translation :: get($application->get_name(), '', $link)));
        }
        
        $this->display_header();
        echo $tabs->render();
        $this->display_footer();
    }

    function get_rights($application_id, $role_id)
    {
        $parameters = new DataClassRetrievesParameters(new EqualityCondition(\application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID, $application_id));
        $rights = \application\atlantis\application\right\DataManager :: retrieves(\application\atlantis\application\right\Right :: class_name(), $parameters);
        $properties = $this->get_display_rights($rights);
        $table = new PropertiesTable($properties);
        
        $table->setAttribute('style', 'margin-top: 1em; margin-bottom: 0;');
        
        return $table->toHtml();
    }

    function get_display_rights($rights)
    {
        $properties = array();
        
        while ($right = $rights->next_result())
        {
            $link = $this->get_url();
            $properties[$right->get_name()] = '<a href="' . $link . '">' . $right->get_description() . '</a>';
        
        }
        return $properties;
    }

    public function get_object_table_condition($object_table_class_name)
    {
        // TODO Auto-generated method stub
    
    }

}
?>