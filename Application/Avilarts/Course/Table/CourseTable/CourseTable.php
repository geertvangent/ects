<?php
namespace Ehb\Application\Avilarts\Course\Table\CourseTable;

use Chamilo\Libraries\Format\Table\Extension\RecordTable\RecordTable;
use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Course\Manager;

/**
 * This class describes a table for the course object
 * 
 * @package \application\Avilarts\course
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
class CourseTable extends RecordTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_COURSE_ID;

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the available table actions
     */
    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, self :: TABLE_IDENTIFIER);
        
        $actions->add_form_action(
            new TableFormAction(
                array(Manager :: PARAM_ACTION => Manager :: ACTION_DELETE), 
                Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        
        return $actions;
    }
}
