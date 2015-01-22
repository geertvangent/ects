<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\GroupBrowser;

use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Platform\Translation;

class GroupBrowserTableColumnModel extends \Chamilo\Core\Group\Table\GroupRelUser\GroupRelUserTableColumnModel
{

    /**
     * Constructor
     */
    public function initialize_columns()
    {
        parent :: initialize_columns();
        $this->add_column(new StaticTableColumn(Translation :: get('Users', null, 'user')));
        $this->add_column(new StaticTableColumn(Translation :: get('Subgroups')));
    }
}
