<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\UserBrowser;

use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\Table\TableCellRenderer;

/**
 * $Id: user_browser_table_cell_renderer.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * 
 * @package user.lib.user_manager.component.user_browser
 */

/**
 * Cell renderer for the user object browser table
 */
class UserBrowserTableCellRenderer extends TableCellRenderer implements TableColumnModelActionsColumnSupport
{

    public function get_object_actions($user)
    {
        $toolbar = new Toolbar();
        
        $profile_link = $this->get_component()->get_module_link(
            'Ehb\Application\Discovery\Module\profile\Implementation\Bamaflex', 
            $user->get_id(), 
            false);
        if ($profile_link)
        {
            $toolbar->add_item($profile_link);
        }
        
        return $toolbar->as_html();
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableCellRenderer::render_id_cell()
     */
    public function render_id_cell($result)
    {
        // TODO Auto-generated method stub
    }
}
