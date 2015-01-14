<?php
namespace Application\EhbSync\bamaflex\synchronization\type;

use libraries\utilities\Utilities;

/**
 *
 * @package ehb.sync;
 */
class UserSynchronization extends Synchronization
{
    const RESULT_PROPERTY_PERSON_ID = 'id';
    const RESULT_PROPERTY_ACADEMIC_YEAR = 'schooljaar';
    const RESULT_PROPERTY_EMAIL_EMPLOYEE = 'email_employee';
    const RESULT_PROPERTY_EMAIL_STUDENT = 'email_student';
    const RESULT_PROPERTY_FIRST_NAME = 'first_name';
    const RESULT_PROPERTY_LAST_NAME = 'last_name';
    const RESULT_PROPERTY_STATUS = 'status';
    const RESULT_PROPERTY_QUOTA = 'quota';

    public function run()
    {
        foreach (self :: get_user_types() as $type)
        {
            $synchronization = self :: factory($type);
            $synchronization->run();
        }
    }

    public static function get_user_types()
    {
        $types = array();
        $types[] = 'create';
        $types[] = 'update';

        return $types;
    }

    public static function factory($type)
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'UserSynchronization';
        if (class_exists($class))
        {
            return new $class();
        }
    }

    public function get_type()
    {
        return 'person';
    }
}
