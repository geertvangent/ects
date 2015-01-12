<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\GroupBrowser;

use Chamilo\Core\Group\GroupRelUserTableColumnModel;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\StaticTableColumn;

class GroupBrowserTableColumnModel extends GroupRelUserTableColumnModel
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
