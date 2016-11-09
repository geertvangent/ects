<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Table\User;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;

/**
 * Cell renderer for the user object browser table
 */
class UserTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{
    
    // Inherited
    public function render_cell($column, $user)
    {
        // Add special features here
        switch ($column->get_name())
        {
            // Exceptions that need post-processing go here ...
            case User::PROPERTY_STATUS :
                if ($user->get_status() == User::STATUS_TEACHER)
                {
                    return Translation::get('CourseAdmin');
                }
                else
                {
                    return Translation::get('Student');
                }
        }
        
        return parent::render_cell($column, $user);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport::get_actions()
     */
    public function get_actions($user)
    {
        $toolbar = new Toolbar();
        
        $toolbar->add_item(
            new ToolbarItem(
                Translation::get('CalendarBrowser'), 
                Theme::getInstance()->getImagePath(Manager::package(), 'Logo/16'), 
                $this->get_component()->get_browser_url($user), 
                ToolbarItem::DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
