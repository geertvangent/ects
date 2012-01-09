<?php
namespace application\discovery\module\group\implementation\bamaflex;

use common\libraries\Request;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\Display;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\group\Module
{
    const PARAM_SOURCE = 'source';
    
    private $cache_groups = array();

    function get_group_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_TRAINING_ID), Request :: get(self :: PARAM_SOURCE));
    }

    function get_groups_data($type)
    {
        if (! isset($this->cache_groups[$type]))
        {
            $groups = array();
            foreach ($this->get_groups() as $group)
            {
                if ($group->get_type() == $type)
                {
                    $groups[] = $group;
                }
            }
            
            $this->cache_groups[$type] = $groups;
        }
        return $this->cache_groups[$type];
    }

    function has_groups($type)
    {
        if ($type)
        {
            return count($this->get_groups_data($type));
        }
        else
        {
            return count($this->get_groups()) > 0;
        }
    }

    function get_groups_table($type)
    {
        $groups = $this->get_groups_data($type);
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $group_user_module_instance = \application\discovery\Module :: exists('application\discovery\module\group_user\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($groups as $key => $group)
        {
            $row = array();
            $row[] = $group->get_code();
            $row[] = $group->get_description();
            
            if ($group_user_module_instance)
            {
                $parameters = new \application\discovery\module\group_user\implementation\bamaflex\Parameters($group->get_type_id(), $group->get_source(), $group->get_type());
                $url = $this->get_instance_url($group_user_module_instance->get_id(), $parameters);
                $toolbar_item = new ToolbarItem(Translation :: get('Users'), Theme :: get_image_path('application\discovery\module\group_user\implementation\bamaflex') . 'logo/16.png', $url, ToolbarItem :: DISPLAY_ICON);
                
                $row[] = $toolbar_item->as_html();
            }
            else
            {
                $row[] = ' ';
            }
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('Code'), false);
        $table->getHeader()->setColAttributes(0, 'class="code"');
        
        $table->set_header(1, Translation :: get('Description'), false);
        
        $table->set_header(2, ' ', false);
        
        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\group\Module::render()
     */
    function render()
    {
        //        $entities = array();
        //        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        //        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        //        
        //        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_group_parameters()))
        //        {
        //            Display :: not_allowed();
        //        }
        //        
        $html = array();
        
        if ($this->has_groups())
        {
            $tabs = new DynamicTabsRenderer('group');
            
            if ($this->has_groups(Group :: TYPE_CLASS))
            {
                $tabs->add_tab(new DynamicContentTab(Group :: TYPE_CLASS, Translation :: get(Group :: type_string(Group :: TYPE_CLASS)), Theme :: get_image_path() . 'type/' . Group :: TYPE_CLASS . '.png', $this->get_groups_table(Group :: TYPE_CLASS)->as_html()));
            }
            if ($this->has_groups(Group :: TYPE_CUSTOM))
            {
                $tabs->add_tab(new DynamicContentTab(Group :: TYPE_CUSTOM, Translation :: get(Group :: type_string(Group :: TYPE_CUSTOM)), Theme :: get_image_path() . 'type/' . Group :: TYPE_CUSTOM . '.png', $this->get_groups_table(Group :: TYPE_CUSTOM)->as_html()));
            }
            if ($this->has_groups(Group :: TYPE_TRAINING))
            {
                $tabs->add_tab(new DynamicContentTab(Group :: TYPE_TRAINING, Translation :: get(Group :: type_string(Group :: TYPE_TRAINING)), Theme :: get_image_path() . 'type/' . Group :: TYPE_TRAINING . '.png', $this->get_groups_table(Group :: TYPE_TRAINING)->as_html()));
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