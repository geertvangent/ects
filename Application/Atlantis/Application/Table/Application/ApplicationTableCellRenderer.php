<?php
namespace Ehb\Application\Atlantis\Application\Table\Application;

use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Manager;

class ApplicationTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function get_actions($application)
    {
        $toolbar = new Toolbar();
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES), 
                    Theme :: getInstance()->getCommonImagePath() . 'action_edit.png', 
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_EDIT, 
                            Manager :: PARAM_APPLICATION_ID => $application->get_id())), 
                    ToolbarItem :: DISPLAY_ICON));
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES), 
                    Theme :: getInstance()->getCommonImagePath() . 'action_delete.png', 
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DELETE, 
                            Manager :: PARAM_APPLICATION_ID => $application->get_id())), 
                    ToolbarItem :: DISPLAY_ICON));
        }
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('ManageRight'), 
                Theme :: getInstance()->getCommonImagePath() . 'action_rights.png', 
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_MANAGE_RIGHT, 
                        Manager :: PARAM_APPLICATION_ID => $application->get_id())), 
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('TypeName', null, '\application\atlantis\role\entitlement'), 
                Theme :: getInstance()->getImagePath('\application\atlantis\role\entitlement') . 'logo/16.png', 
                $this->get_component()->get_url(
                    array(
                        \Ehb\Application\Atlantis\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE, 
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITLEMENT, 
                        \Ehb\Application\Atlantis\Role\Entitlement\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entitlement\Manager :: ACTION_BROWSE, 
                        Manager :: PARAM_APPLICATION_ID => $application->get_id())), 
                ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
