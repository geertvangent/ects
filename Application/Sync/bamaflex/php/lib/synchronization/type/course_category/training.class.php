<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

use common\libraries\Utilities;

class TrainingCourseCategorySynchronization extends CourseCategorySynchronization
{
    CONST IDENTIFIER = 'TRA';
    
    const RESULT_PROPERTY_TRAINING = 'name';
    const RESULT_PROPERTY_TRAINING_ID = 'id';

    /**
     *
     * @return string
     */
    function get_code()
    {
        $parent = $this->get_synchronization();
        return self :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_TRAINING_ID);
    }

    /**
     *
     * @return string
     */
    function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_TRAINING);
    }

    /**
     *
     * @return int
     */
    function get_department_id()
    {
        return $this->get_user_type()->get_department()->get_parameter(DepartmentCourseCategorySynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);
    }

}
?>