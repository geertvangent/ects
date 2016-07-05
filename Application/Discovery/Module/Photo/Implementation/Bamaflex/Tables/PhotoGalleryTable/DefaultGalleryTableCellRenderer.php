<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Tables\PhotoGalleryTable;

use Chamilo\Libraries\Format\Table\Extension\GalleryTable\GalleryTableCellRenderer;

abstract class DefaultGalleryTableCellRenderer extends GalleryTableCellRenderer
{

    public function render_cell($user)
    {
        $html = array();
        $html[] = $this->renderContent($user);
        return implode(PHP_EOL, $html);
    }

    public function render_id_cell($user)
    {
        return $user->get_id();
    }
}
