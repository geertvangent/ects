<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\GroupRelUserBrowser;

use Chamilo\Core\Group\Storage\DataClass\GroupRelUser;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Core\Group\Table\GroupRelUser\GroupRelUserTableCellRenderer;

class GroupRelUserBrowserTableCellRenderer extends GroupRelUserTableCellRenderer
{

    /**
     * The repository browser component
     */
    private $browser;

    /**
     * Constructor
     *
     * @param RepositoryManagerBrowserComponent $browser
     */
    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    // Inherited
    public function render_cell($column, $groupreluser)
    {
        if ($column === GroupRelUserBrowserTableColumnModel :: get_modification_column())
        {
            return $this->get_modification_links($groupreluser);
        }

        // Add special features here
        switch ($column->get_name())
        {
            // Exceptions that need post-processing go here ...
            case GroupRelUser :: PROPERTY_USER_ID :
                $user_id = parent :: render_cell($column, $groupreluser);
                $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(
                    \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
                    (int) $user_id);
                return $user->get_fullname();
        }
        return parent :: render_cell($column, $groupreluser);
    }

    private function get_modification_links($groupreluser)
    {
        $toolbar = new Toolbar();

        $profile_link = $this->browser->get_module_link(
            'application\discovery\module\profile\implementation\bamaflex',
            $groupreluser->get_user_id(),
            false);
        if ($profile_link)
        {
            $toolbar->add_item($profile_link);
        }

        return $toolbar->as_html();
    }
}
