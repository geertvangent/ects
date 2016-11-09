<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\GroupRelUserBrowser;

use Chamilo\Core\Group\Table\GroupRelUser\GroupRelUserTableCellRenderer;
use Chamilo\Libraries\Format\Structure\Toolbar;

class GroupRelUserBrowserTableCellRenderer extends GroupRelUserTableCellRenderer
{

    public function get_actions($groupreluser)
    {
        $toolbar = new Toolbar();
        
        $profile_link = $this->get_component()->get_module_link(
            'Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex', 
            $groupreluser->get_user_id(), 
            false);
        if ($profile_link)
        {
            $toolbar->add_item($profile_link);
        }
        
        return $toolbar->as_html();
    }
}
