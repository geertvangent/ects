<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use common\libraries\Utilities;

abstract class TrainingGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'TRA';

    const RESULT_PROPERTY_TRAINING = 'name';
    const RESULT_PROPERTY_TRAINING_ID = 'id';

    /**
     * @return string
     */
    function get_code()
    {
        $parent = $this->get_synchronization();
        return self :: IDENTIFIER . '_' . $this->get_group_type() . '_' . $this->get_parameter(self :: RESULT_PROPERTY_TRAINING_ID);
    }

    /**
     * @return string
     */
    function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_TRAINING);
    }

    /**
     * @return int
     */
    function get_department_id()
    {
        return $this->get_user_type()->get_department()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);
    }

    /**
     * @return string
     */
    abstract function get_group_type();

    function get_user_type()
    {
        return $this->get_synchronization();
    }
}
?>