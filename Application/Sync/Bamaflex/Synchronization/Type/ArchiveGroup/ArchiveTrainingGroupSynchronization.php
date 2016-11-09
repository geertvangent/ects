<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\ArchiveGroup;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\ArchiveGroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
abstract class ArchiveTrainingGroupSynchronization extends ArchiveGroupSynchronization
{
    CONST IDENTIFIER = 'TRA';
    const RESULT_PROPERTY_TRAINING = 'name';
    const RESULT_PROPERTY_TRAINING_ID = 'id';

    /**
     *
     * @return string
     */
    public function get_code()
    {
        $parent = $this->get_synchronization();
        return ($this->is_old() ? 'OLD_' : '') . self::IDENTIFIER . '_' . $this->get_group_type() . '_' .
             $this->get_parameter(self::RESULT_PROPERTY_TRAINING_ID);
    }

    /**
     *
     * @return string
     */
    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_TRAINING);
    }

    /**
     *
     * @return int
     */
    public function get_department_id()
    {
        return $this->get_user_type()->get_department()->get_parameter(
            ArchiveDepartmentGroupSynchronization::RESULT_PROPERTY_DEPARTMENT_ID);
    }

    /**
     *
     * @return string
     */
    abstract public function get_group_type();

    public function get_user_type()
    {
        return $this->get_synchronization();
    }
}
