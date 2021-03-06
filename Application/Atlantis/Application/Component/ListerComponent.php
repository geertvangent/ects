<?php
namespace Ehb\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Manager;
use Ehb\Application\Atlantis\Application\Storage\DataClass\Application;
use Ehb\Application\Atlantis\Application\Storage\DataManager;

class ListerComponent extends Manager implements TableSupport
{

    public function run()
    {
        $renderer_name = Utilities::get_classname_from_object($this, true);
        $tabs = new DynamicVisualTabsRenderer(
            $renderer_name, 
            $this->get_rights(Request::get(self::PARAM_APPLICATION_ID)));
        
        // for each application, a list of rights
        $applications = DataManager::retrieves(Application::class_name(), new DataClassRetrievesParameters());
        
        while ($application = $applications->next_result())
        {
            $tabs->add_tab(
                new DynamicContentTab(
                    $application->get_id, 
                    Translation::get($application->get_name()), 
                    '', 
                    $this->get_rights($application->get_id())));
        }
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function get_rights($application_id)
    {
        $parameters = new DataClassRetrievesParameters(
            new EqualityCondition(
                new PropertyConditionVariable(
                    \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::class_name(), 
                    \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::PROPERTY_APPLICATION_ID), 
                new StaticConditionVariable($application_id)));
        $rights = DataManager::retrieves(
            \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::class_name(), 
            $parameters);
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

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
