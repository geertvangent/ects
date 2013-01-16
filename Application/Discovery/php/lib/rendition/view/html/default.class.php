<?php
namespace application\discovery;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\BreadcrumbTrail;

class HtmlDefaultRendition extends HtmlRendition
{

    function render()
    {
        $html = array();
        return implode("\n", $html);
    }

    static function add_export_action(\application\discovery\RenditionImplementation $rendition_implementation)
    {
        $export_parameters = array_merge($rendition_implementation->get_module_parameters()->get_parameters(),
                array(
                        \application\discovery\DiscoveryManager :: PARAM_VIEW => \application\discovery\HtmlRendition :: VIEW_XLSX));
        $url = $rendition_implementation->get_context()->get_url($export_parameters);

        BreadcrumbTrail :: get_instance()->add_extra(
                new ToolbarItem(Translation :: get('Excel'), Theme :: get_common_image_path() . 'export_excel.png', $url));
    }
}
?>