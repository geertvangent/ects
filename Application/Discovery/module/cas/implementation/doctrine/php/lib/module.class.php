<?php
namespace application\discovery\module\cas\implementation\doctrine;

use common\libraries\Theme;
use common\libraries\Display;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Translation;

use application\discovery\SortableTable;
use application\discovery\module\cas\DataManager;

class Module extends \application\discovery\module\cas\Module
{
    private $action_statistics;

    function get_action_statistics($action_id)
    {
        if (! isset($this->action_statistics))
        {
            $this->action_statistics = array();
            
            foreach ($this->get_cas_statistics() as $cas_statistic)
            {
                if ($cas_statistic->get_application_id())
                {
                    $this->action_statistics[$cas_statistic->get_action_id()][$cas_statistic->get_application_id()][] = $cas_statistic;
                }
                else
                {
                    $this->action_statistics[$cas_statistic->get_action_id()][0][] = $cas_statistic;
                }
            }
        }
        return $this->action_statistics[$action_id];
    }

    function get_statistics_table($action_id)
    {
        $action_statistics = $this->get_action_statistics($action_id);
        
        if (count($action_statistics) == 1 && count($action_statistics[0]) > 0)
        {
            $data = array();
            foreach ($action_statistics[0] as $key => $action_statistic)
            {
                $row = array();
                $row[] = $action_statistic->get_date();
                $data[] = $row;
            }
            $table = new SortableTable($data);
            $table->set_header(0, Translation :: get('Date'), false);
            
            return $table->toHTML();
        }
        else
        {
            $tabs = new DynamicTabsRenderer('statistics_list_' . $action_id);
            foreach ($action_statistics as $application_id => $application_statistics)
            {
                $data = array();
                
                foreach ($application_statistics as $key => $application_statistic)
                {
                    $row = array();
                    $row[] = $application_statistic->get_date();
                    $data[] = $row;
                }
                $table = new SortableTable($data);
                $table->set_header(0, Translation :: get('Date'), false);
                $applications = $this->get_applications();
                $tabs->add_tab(new DynamicContentTab($application_id, $applications[$application_id]->get_title(), Theme :: get_image_path() . 'application/' . $application_id . '.png', $table->toHTML()));
            
            }
            return $tabs->render();
        }
    }
    
    /* (non-PHPdoc)
     * @see application\discovery\module\enrollment.Module::render()
    */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_cas_parameters()))
        {
            Display :: not_allowed();
        }
        
        $html = array();
        if (count($this->get_cas_statistics()) > 0)
        {
            $actions = DataManager :: get_instance($this->get_module_instance())->retrieve_actions($this->get_cas_parameters());
            
            $tabs = new DynamicTabsRenderer('statistics_list');
            
            foreach ($actions as $action)
            {
                $tabs->add_tab(new DynamicContentTab($action->get_id(), Translation :: get($action->get_name()), Theme :: get_image_path() . 'action/' . $action->get_id() . '.png', $this->get_statistics_table($action->get_id())));
            
            }
            
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
}
?>