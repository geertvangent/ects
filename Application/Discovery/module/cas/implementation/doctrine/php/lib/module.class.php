<?php
namespace application\discovery\module\cas\implementation\doctrine;

use application\discovery\SortableTable;

use common\libraries\Filesystem;
use common\libraries\Path;
use common\libraries\Theme;
use common\libraries\Display;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Translation;

// use application\discovery\SortableTable;
use application\discovery\module\cas\DataManager;

class Module extends \application\discovery\module\cas\Module
{
    private $action_statistics;

    function get_action_statistics($action)
    {
        if (! isset($this->action_statistics))
        {
            $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/cas_action_statistics/' . md5(serialize($this->get_cas_parameters()));
            
            if (! file_exists($path))
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
                Filesystem :: write_to_file($path, serialize($this->action_statistics));
            }
            else
            {
                $this->action_statistics = unserialize(file_get_contents($path));
            }
        
        }
        return $this->action_statistics[$action->get_id()];
    }

    function get_statistics_table($action)
    {
        $action_statistics = $this->get_action_statistics($action);
        
        if (count($action_statistics) == 1 && count($action_statistics[0]) > 0)
        {
            $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/data/' . md5(serialize(array(
                    $this->get_cas_parameters()->get_user_id(), 0, $action->get_id())));
            
            if (! file_exists($path))
            {
                $data = array();
                
                foreach ($action_statistics[0] as $key => $action_statistic)
                {
                    $row = array();
                    $row[] = $action_statistic->get_date();
                    $data[] = $row;
                }
                Filesystem :: write_to_file($path, serialize($data));
            }
            else
            {
                $data = unserialize(file_get_contents($path));
            }
            
            $table = new SortableTable($data);
            $table->set_header(0, Translation :: get('Date'), false);
            
            return $table->toHTML();
        }
        else
        {
            $tabs = new DynamicTabsRenderer('statistics_list_' . $action->get_id());
            foreach ($action_statistics as $application_id => $application_statistics)
            {
                $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/data/' . md5(serialize(array(
                        $this->get_cas_parameters()->get_user_id(), $application_id, $action->get_id())));
                
                if (! file_exists($path))
                {
                    $data = array();
                    
                    foreach ($application_statistics as $key => $application_statistic)
                    {
                        $row = array();
                        $row[] = $application_statistic->get_date();
                        $data[] = $row;
                    }
                    Filesystem :: write_to_file($path, serialize($data));
                }
                else
                {
                    $data = unserialize(file_get_contents($path));
                }
                
                $applications = $this->get_applications();
                
                $sub_tabs = new DynamicTabsRenderer('statistics_list_' . $action->get_id() . '_' . $application_id);
                
                $html = array();
                $graph = new GraphRenderer($this, $this->get_cas_parameters()->get_user_id(), $applications[$application_id], $action);
                $sub_tabs->add_tab(new DynamicContentTab(1, Translation :: get('Chart'), Theme :: get_image_path(__NAMESPACE__) . 'sub_tabs/1.png', $graph->chart()));
                $sub_tabs->add_tab(new DynamicContentTab(2, Translation :: get('Table'), Theme :: get_image_path(__NAMESPACE__) . 'sub_tabs/2.png', $graph->table()));
                
                $table = new SortableTable($data);
                $table->set_header(0, Translation :: get('Date'), false);
                $sub_tabs->add_tab(new DynamicContentTab(3, Translation :: get('DatesTable'), Theme :: get_image_path(__NAMESPACE__) . 'sub_tabs/3.png', $table->toHTML()));
                
                $tabs->add_tab(new DynamicContentTab($application_id, $applications[$application_id]->get_title(), Theme :: get_image_path() . 'application/' . $application_id . '.png', $sub_tabs->render()));
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
                $tabs->add_tab(new DynamicContentTab($action->get_id(), Translation :: get($action->get_name()), Theme :: get_image_path() . 'action/' . $action->get_id() . '.png', $this->get_statistics_table($action)));
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