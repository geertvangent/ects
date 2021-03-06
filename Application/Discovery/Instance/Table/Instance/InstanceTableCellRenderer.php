<?php
namespace Ehb\Application\Discovery\Instance\Table\Instance;

use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Column\DisplayOrderPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\StringUtilities;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\Instance\Manager;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Instance\Storage\DataManager;

class InstanceTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function render_cell($column, $module_instance)
    {
        switch ($column->get_name())
        {
            
            case Instance::PROPERTY_TYPE :
                $name = htmlentities(Translation::get('TypeName', null, $module_instance->get_type()));
                return '<img src="' . Theme::getInstance()->getImagesPath($module_instance->get_type()) .
                     'Logo/22.png" alt="' . $name . '" title="' . $name . '"/>';
            case Instance::PROPERTY_TITLE :
                return Translation::get('TypeName', null, $module_instance->get_type());
            case Instance::PROPERTY_DESCRIPTION :
                return StringUtilities::getInstance()->truncate(
                    Translation::get('TypeDescription', null, $module_instance->get_type()), 
                    50);
        }
        return parent::render_cell($column, $module_instance);
    }

    public function get_actions($module_instance)
    {
        $toolbar = new Toolbar();
        
        $allowed = $this->check_move_allowed($module_instance);
        
        if ($this->is_order_column_type(DisplayOrderPropertyTableColumn::class_name()))
        {
            if ($allowed["moveup"])
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation::get('MoveUp', null, Utilities::COMMON_LIBRARIES), 
                        Theme::getInstance()->getCommonImagePath('Action/Up'), 
                        $this->get_component()->get_url(
                            array(
                                Manager::PARAM_ACTION => Manager::ACTION_MOVE_INSTANCE, 
                                \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $module_instance->get_id(), 
                                \Ehb\Application\Discovery\Manager::PARAM_DIRECTION => \Ehb\Application\Discovery\Manager::PARAM_DIRECTION_UP)), 
                        ToolbarItem::DISPLAY_ICON));
            }
            else
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation::get('MoveUpNotAvailable', null, Utilities::COMMON_LIBRARIES), 
                        Theme::getInstance()->getCommonImagePath('Action/UpNa'), 
                        null, 
                        ToolbarItem::DISPLAY_ICON));
            }
            
            if ($allowed["movedown"])
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation::get('MoveDown', null, Utilities::COMMON_LIBRARIES), 
                        Theme::getInstance()->getCommonImagePath('Action/Down'), 
                        $this->get_component()->get_url(
                            array(
                                Manager::PARAM_ACTION => Manager::ACTION_MOVE_INSTANCE, 
                                \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $module_instance->get_id(), 
                                \Ehb\Application\Discovery\Manager::PARAM_DIRECTION => \Ehb\Application\Discovery\Manager::PARAM_DIRECTION_DOWN)), 
                        ToolbarItem::DISPLAY_ICON));
            }
            else
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation::get('MoveDownNotAvailable', null, Utilities::COMMON_LIBRARIES), 
                        Theme::getInstance()->getCommonImagePath('Action/DownNa'), 
                        null, 
                        ToolbarItem::DISPLAY_ICON));
            }
        }
        
        if ($module_instance->is_enabled())
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation::get('Deactivate', null, Utilities::COMMON_LIBRARIES), 
                    Theme::getInstance()->getCommonImagePath('Action/Deactivate'), 
                    $this->get_component()->get_url(
                        array(
                            Manager::PARAM_ACTION => Manager::ACTION_DEACTIVATE_INSTANCE, 
                            \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $module_instance->get_id())), 
                    ToolbarItem::DISPLAY_ICON, 
                    true));
        }
        else
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation::get('Activate', null, Utilities::COMMON_LIBRARIES), 
                    Theme::getInstance()->getCommonImagePath('Action/Activate'), 
                    $this->get_component()->get_url(
                        array(
                            Manager::PARAM_ACTION => Manager::ACTION_ACTIVATE_INSTANCE, 
                            \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $module_instance->get_id())), 
                    ToolbarItem::DISPLAY_ICON, 
                    true));
        }
        
        $toolbar->add_item(
            new ToolbarItem(
                Translation::get('Edit', null, Utilities::COMMON_LIBRARIES), 
                Theme::getInstance()->getCommonImagePath('Action/Edit'), 
                $this->get_component()->get_url(
                    array(
                        Manager::PARAM_ACTION => Manager::ACTION_UPDATE_INSTANCE, 
                        \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $module_instance->get_id())), 
                ToolbarItem::DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation::get('Delete', null, Utilities::COMMON_LIBRARIES), 
                Theme::getInstance()->getCommonImagePath('Action/Delete'), 
                $this->get_component()->get_url(
                    array(
                        Manager::PARAM_ACTION => Manager::ACTION_DELETE_INSTANCE, 
                        \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $module_instance->get_id())), 
                ToolbarItem::DISPLAY_ICON, 
                true));
        return $toolbar->as_html();
    }

    protected function check_move_allowed($module_instance)
    {
        $moveup_allowed = true;
        $movedown_allowed = true;
        
        $count = DataManager::count(
            Instance::class_name(), 
            new DataClassCountParameters(
                new EqualityCondition(
                    new PropertyConditionVariable(Instance::class_name(), Instance::PROPERTY_CONTENT_TYPE), 
                    new StaticConditionVariable($module_instance->get_content_type()))));
        
        if ($count == 1)
        {
            $moveup_allowed = false;
            $movedown_allowed = false;
        }
        else
        {
            if ($module_instance->get_display_order() == 1)
            {
                $moveup_allowed = false;
            }
            else
            {
                if ($module_instance->get_display_order() == $count)
                {
                    $movedown_allowed = false;
                }
            }
        }
        
        return array('moveup' => $moveup_allowed, 'movedown' => $movedown_allowed);
    }
}
