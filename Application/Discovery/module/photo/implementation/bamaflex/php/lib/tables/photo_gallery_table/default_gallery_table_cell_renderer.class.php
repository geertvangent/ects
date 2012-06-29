<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use user\User;

use common\libraries\GalleryObjectTableCellRenderer;

abstract class DefaultGalleryTableCellRenderer implements GalleryObjectTableCellRenderer
{

    function __construct()
    {
    }

    function render_cell($user)
    {
        $html = array();
        $html[] = $this->get_cell_content($user);
        return implode("\n", $html);
    }

    function render_id_cell($user)
    {
        return $user->get_id();
    }

    abstract function get_cell_content(User $user);
}
?>