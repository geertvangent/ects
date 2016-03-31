<?php
namespace Ehb\Application\Avilarts\Rights\Entities\Group;

use Chamilo\Core\Rights\Editor\Table\LocationEntity\LocationEntityTableColumnModel;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;

/**
 * Cell renderer for the course group entity browser
 * 
 * @author Sven Vanpoucke
 */
class CourseGroupEntityTableColumnModel extends LocationEntityTableColumnModel
{
    const COLUMN_USERS = 'users';
    const COLUMN_SUBGROUPS = 'subgroups';

    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(CourseGroup :: class_name(), CourseGroup :: PROPERTY_NAME));
        $this->add_column(
            new DataClassPropertyTableColumn(CourseGroup :: class_name(), CourseGroup :: PROPERTY_DESCRIPTION));
        $this->add_column(new StaticTableColumn(self :: COLUMN_USERS));
        $this->add_column(new StaticTableColumn(self :: COLUMN_SUBGROUPS));
        
        parent :: initialize_columns();
    }
}
