<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseSections\Component\CourseSections;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSection;

/**
 * $Id: course_sections_browser_table_column_model.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.course_sections.component.course_sections_browser
 */
/**
 * Table column model for the course browser table
 */
class CourseSectionsTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(CourseSection :: class_name(), CourseSection :: PROPERTY_NAME));
    }
}
