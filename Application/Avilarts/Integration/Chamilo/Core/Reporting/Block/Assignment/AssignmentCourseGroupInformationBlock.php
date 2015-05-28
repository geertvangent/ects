<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment;

use Chamilo\Libraries\Platform\Translation;

/**
 * Defines the reporting block for course group information.
 * Only implements the methods necessary to customise
 * otherwise common information display defined in the superclass.
 * 
 * @author Anthony Hurst (Hogeschool Gent)
 */
class AssignmentCourseGroupInformationBlock extends AssignmentSubmitterInformationBlock
{

    /**
     * Defines the row title to be displayed in parent::ROW_SUBMITTER.
     */
    protected function define_row_submitter_title()
    {
        return Translation :: get('CourseGroup');
    }
}
