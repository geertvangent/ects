<?php
namespace Ehb\Application\Discovery\Rendition\View\Html;

use Ehb\Application\Discovery\Rendition\Format\HtmlRendition;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

class HtmlDefaultRendition extends HtmlRendition
{

    public function render()
    {
        return '';
    }

    public static function add_export_action(
        \Ehb\Application\Discovery\Rendition\RenditionImplementation $rendition_implementation, 
        $type = \Ehb\Application\Discovery\Rendition\Format\HtmlRendition :: VIEW_XLSX)
    {
        $export_parameters = array_merge(
            $rendition_implementation->get_module_parameters()->get_parameters(), 
            array(\Ehb\Application\Discovery\Manager :: PARAM_VIEW => $type));
        $url = $rendition_implementation->get_context()->get_url($export_parameters);
        
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get(Utilities :: underscores_to_camelcase($type)), 
                Theme :: getInstance()->getImagePath() . 'export/' . $type . '.png', 
                $url));
    }
}
