<?php
namespace application\atlantis\application;

use libraries\PropertiesTable;
use libraries\Request;
use libraries\DynamicVisualTabsRenderer;
use libraries\DataClassRetrievesParameters;
use libraries\EqualityCondition;
use libraries\Translation;
use libraries\DynamicContentTab;
use libraries\Utilities;
use libraries\TableSupport;
use libraries\StaticConditionVariable;
use libraries\PropertyConditionVariable;

class ListerComponent extends Manager implements TableSupport
{

    public function run()
    {
        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicVisualTabsRenderer(
            $renderer_name,
            $this->get_rights(Request :: get(self :: PARAM_APPLICATION_ID)));

        // for each application, a list of rights
        $applications = DataManager :: retrieves(Application :: class_name());

        while ($application = $applications->next_result())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    $application->get_id,
                    Translation :: get($application->get_name()),
                    '',
                    $this->get_rights($application->get_id())));
        }

        $this->display_header();
        echo $tabs->render();
        $this->display_footer();
    }

    public function get_rights($application_id)
    {
        $parameters = new DataClassRetrievesParameters(
            new EqualityCondition(
                new PropertyConditionVariable(
                    \application\atlantis\application\right\Right :: class_name(),
                    \application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID),
                new StaticConditionVariable($application_id)));
        $rights = DataManager :: retrieves(\application\atlantis\application\right\Right :: class_name(), $parameters);
        $properties = $this->get_display_rights($rights);
        $table = new PropertiesTable($properties);

        $table->setAttribute('style', 'margin-top: 1em; margin-bottom: 0;');

        return $table->toHtml();
    }

    public function get_display_rights($rights)
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
	/* (non-PHPdoc)
     * @see \libraries\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub

    }

}
