<?php
namespace application\atlantis\application;

use common\libraries\PropertiesTable;
use common\libraries\Request;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\EqualityCondition;
use common\libraries\Translation;
use common\libraries\DynamicContentTab;
use common\libraries\Utilities;
use common\libraries\NewObjectTableSupport;

class ListerComponent extends Manager implements NewObjectTableSupport
{

    function run()
    {
        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicVisualTabsRenderer($renderer_name, $this->get_rights(Request :: get(self :: PARAM_APPLICATION_ID)));

        // for each application, a list of rights
        $applications = DataManager :: retrieves(Application :: class_name());

        while ($application = $applications->next_result())
        {
            $tabs->add_tab(new DynamicContentTab($application->get_id, Translation :: get($application->get_name()), '', $this->get_rights($application->get_id())));
        }

        $this->display_header();
        echo $tabs->render();
        $this->display_footer();
    }

    function get_rights($application_id)
    {
        $parameters = new DataClassRetrievesParameters(new EqualityCondition(\application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID, $application_id));
        $rights = DataManager :: retrieves(\application\atlantis\application\right\Right :: class_name(), $parameters);
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
