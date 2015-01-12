<?php
namespace Chamilo\Application\Discovery\Rendition\View\Html;

use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Application\Discovery\Rendition\Format\HtmlRendition;

class HtmlDefaultRendition extends HtmlRendition
{

    public function render()
    {
        return '';
    }

    public static function add_export_action(\Chamilo\Application\Discovery\RenditionImplementation $rendition_implementation,
        $type = \Chamilo\Application\Discovery\HtmlRendition :: VIEW_XLSX)
    {
        $export_parameters = array_merge(
            $rendition_implementation->get_module_parameters()->get_parameters(),
            array(\Chamilo\Application\Discovery\Manager :: PARAM_VIEW => $type));
        $url = $rendition_implementation->get_context()->get_url($export_parameters);

        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get(Utilities :: underscores_to_camelcase($type)),
                Theme :: get_image_path() . 'export/' . $type . '.png',
                $url));
    }
}
