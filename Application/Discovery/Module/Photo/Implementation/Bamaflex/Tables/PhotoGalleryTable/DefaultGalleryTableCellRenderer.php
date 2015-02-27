<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Tables\PhotoGalleryTable;

use Chamilo\Libraries\Format\Table\Extension\GalleryTable\GalleryTableCellRenderer;

abstract class DefaultGalleryTableCellRenderer extends GalleryTableCellRenderer
{

    public function __construct()
    {
    }

    public function render_cell($user)
    {
        $html = array();
        $html[] = $this->get_cell_content($user);
        return implode("\n", $html);
    }

    public function render_id_cell($user)
    {
        return $user->get_id();
    }

    abstract public function get_cell_content(\Chamilo\Core\User\Storage\DataClass\User $user);
}
