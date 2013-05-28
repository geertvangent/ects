<?php
namespace application\discovery\data_source;

use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ToolbarItem;
use common\libraries\Toolbar;
use common\libraries\Theme;

class InstanceBrowserTableCellRenderer extends DefaultInstanceTableCellRenderer
{

    private $browser;

    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    public function render_cell($column, $module_instance)
    {
        if ($column === InstanceBrowserTableColumnModel :: get_modification_column())
        {
            return $this->get_modification_links($module_instance);
        }

        return parent :: render_cell($column, $module_instance);
    }

    private function get_modification_links($module_instance)
    {
        $toolbar = new Toolbar();

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_edit.png',
                $this->browser->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_UPDATE_INSTANCE,
                        Manager :: PARAM_DATA_SOURCE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_delete.png',
                $this->browser->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_DELETE_INSTANCE,
                        Manager :: PARAM_DATA_SOURCE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON,
                true));

        return $toolbar->as_html();
    }
}
