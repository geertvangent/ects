<?php
namespace application\discovery\module\training_info;

use common\libraries\Request;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Path;
use common\libraries\WebApplication;
use common\libraries\ResourceManager;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\SortableTable;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';
    
    /**
     * @var multitype:\application\discovery\module\training_info\Faculty
     */
    private $training;
    private $cache_training = array();

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->training = DataManager :: get_instance($module_instance)->retrieve_training($this->get_training_parameters());
    }

    function get_training_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_TRAINING_ID));
    }

    /**
     * @return multitype:\application\discovery\module\training_info\Faculty
     */
    function get_training()
    {
        return $this->training;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\training_info\Module::render()
     */
    function render()
    {
//        $html = array();
        dump($this->get_training());
//        if ($this->has_parameters())
//        {
//           
//            $html[] = $this->get_training_infos_table()->toHTML();
//        }
//        else
//        {
//            $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_application()->get_user_id());
//            
//            $tabs = new DynamicTabsRenderer('training_info_list');
//            foreach ($years as $year)
//            {
//                $tabs->add_tab(new DynamicContentTab($year, $year, null, $this->get_training_infos_table($year)->toHTML()));
//            }
//            $html[] = $tabs->render();
//        
//        }
//        return implode("\n", $html);
    }

}
?>