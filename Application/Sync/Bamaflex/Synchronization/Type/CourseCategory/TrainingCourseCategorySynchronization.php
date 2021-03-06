<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseCategory;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseCategorySynchronization;

/**
 *
 * @package ehb.sync;
 */
class TrainingCourseCategorySynchronization extends CourseCategorySynchronization
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
        return self::IDENTIFIER . '_' . $this->get_parameter(self::RESULT_PROPERTY_TRAINING_ID);
    }

    /**
     *
     * @return string
     */
    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_TRAINING);
    }
}
