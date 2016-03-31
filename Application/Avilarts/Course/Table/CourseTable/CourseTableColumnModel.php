<?php
namespace Ehb\Application\Avilarts\Course\Table\CourseTable;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\RecordTable\RecordTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Course\Storage\DataClass\Course;
use Ehb\Application\Avilarts\CourseType\Storage\DataClass\CourseType;

/**
 * This class describes the column model for the course table
 * 
 * @package \application\Avilarts\course
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
class CourseTableColumnModel extends RecordTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Course :: class_name(), Course :: PROPERTY_VISUAL_CODE));
        $this->add_column(new DataClassPropertyTableColumn(Course :: class_name(), Course :: PROPERTY_TITLE));
        
        $this->add_column(
            new DataClassPropertyTableColumn(Course :: class_name(), Course :: PROPERTY_TITULAR_ID, null, false));
        
        $this->add_column(
            new DataClassPropertyTableColumn(
                CourseType :: class_name(), 
                CourseType :: PROPERTY_TITLE, 
                Translation :: get('CourseType')));
    }
}
