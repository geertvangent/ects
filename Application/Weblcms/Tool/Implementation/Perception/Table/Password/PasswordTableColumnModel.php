<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception;

use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;

class PasswordTableColumnModel extends DataClassTableColumnModel
{

    public function initialize_columns()
    {
        $this->add_column(new StaticTableColumn(Translation :: get('User')));
        $this->add_column(new DataClassPropertyTableColumn(Password :: class_name(), Password :: PROPERTY_PASSWORD));
    }
}
