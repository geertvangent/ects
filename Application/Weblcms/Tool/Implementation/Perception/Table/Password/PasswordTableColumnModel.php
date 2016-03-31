<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Table\Password;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataClass\Password;

class PasswordTableColumnModel extends DataClassTableColumnModel
{

    public function initialize_columns()
    {
        $this->add_column(new StaticTableColumn(Translation :: get('User')));
        $this->add_column(new DataClassPropertyTableColumn(Password :: class_name(), Password :: PROPERTY_PASSWORD));
    }
}
