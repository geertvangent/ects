<?php
namespace application\atlantis\role;

use common\libraries\NewObjectTableCellRenderer;
use common\libraries\NewObjectTableCellRendererActionsColumnSupport;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Toolbar;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\PlatformSetting;

class RoleTableCellRenderer extends NewObjectTableCellRenderer implements NewObjectTableCellRendererActionsColumnSupport
{

    function get_object_actions($role)
    {
        $toolbar = new Toolbar();
        
        return $toolbar->as_html();
    }
}
?>
