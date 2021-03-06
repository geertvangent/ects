<?php
namespace Ehb\Application\Discovery\DataSource\Table\Instance;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Ehb\Application\Discovery\DataSource\Storage\DataClass\Instance;

class InstanceTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Instance::class_name(), Instance::PROPERTY_TYPE));
        $this->add_column(new DataClassPropertyTableColumn(Instance::class_name(), Instance::PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Instance::class_name(), Instance::PROPERTY_DESCRIPTION));
    }
}
