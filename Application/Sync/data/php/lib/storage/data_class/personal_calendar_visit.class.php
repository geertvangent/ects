<?php
namespace application\ehb_sync\data;

use libraries\storage\DataClass;

/**
 * Tracks the visits of a user to the personal calendar
 *
 * @package application\ehb_sync\data
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class PersonalCalendarVisit extends DataClass
{
    const CLASS_NAME = __CLASS__;

    // Properties
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_PUBLICATION_ID = 'publication_id';
    const PROPERTY_ACCESS_DATE = 'access_date';
    const PROPERTY_TIME = 'time';

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Returns the default property names of this dataclass
     *
     * @return \string[]
     */
    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_USER_ID,
                self :: PROPERTY_PUBLICATION_ID,
                self :: PROPERTY_ACCESS_DATE,
                self :: PROPERTY_TIME));
    }

    /**
     * **************************************************************************************************************
     * Getters & Setters Functionality *
     * **************************************************************************************************************
     */

    /**
     * Returns the user_id
     *
     * @return int
     */
    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    /**
     * Sets the user_id
     *
     * @param int $user_id
     */
    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }

    /**
     * Returns the publication_id
     *
     * @return int
     */
    public function get_publication_id()
    {
        return $this->get_default_property(self :: PROPERTY_PUBLICATION_ID);
    }

    /**
     * Sets the publication_id
     *
     * @param int $publication_id
     */
    public function set_publication_id($publication_id)
    {
        $this->set_default_property(self :: PROPERTY_PUBLICATION_ID, $publication_id);
    }

    /**
     * Returns the access_date
     *
     * @return int
     */
    public function get_access_date()
    {
        return $this->get_default_property(self :: PROPERTY_ACCESS_DATE);
    }

    /**
     * Sets the access_date
     *
     * @param int $access_date
     */
    public function set_access_date($access_date)
    {
        $this->set_default_property(self :: PROPERTY_ACCESS_DATE, $access_date);
    }

    /**
     * Returns the time
     *
     * @return int
     */
    public function get_time()
    {
        return $this->get_default_property(self :: PROPERTY_TIME);
    }

    /**
     * Sets the time
     *
     * @param int $time
     */
    public function set_time($time)
    {
        $this->set_default_property(self :: PROPERTY_TIME, $time);
    }
}
