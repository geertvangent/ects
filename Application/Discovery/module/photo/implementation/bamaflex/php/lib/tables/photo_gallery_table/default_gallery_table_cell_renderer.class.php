<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\GalleryTableCellRenderer;

abstract class DefaultGalleryTableCellRenderer implements GalleryTableCellRenderer
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

    abstract public function get_cell_content(\core\user\User $user);
}
