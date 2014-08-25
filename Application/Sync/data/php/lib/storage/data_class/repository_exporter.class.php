<?php
namespace application\ehb_sync\data;

use libraries\DataClass;

/**
 * Tracks the visits of a user to a course
 *
 * @package application\ehb_sync\data
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class RepositoryExporter extends DataClass
{
    const CLASS_NAME = __CLASS__;

    // Properties
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_CATEGORY_ID = 'category_id';
    const PROPERTY_CONTENT_OBJECT_ID = 'content_object_id';
    const PROPERTY_TYPE = 'type';
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
                self :: PROPERTY_CATEGORY_ID,
                self :: PROPERTY_CONTENT_OBJECT_ID,
                self :: PROPERTY_TYPE,
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
     * Returns the category_id
     *
     * @return int
     */
    public function get_category_id()
    {
        return $this->get_default_property(self :: PROPERTY_CATEGORY_ID);
    }

    /**
     * Sets the category_id
     *
     * @param int $category_id
     */
    public function set_category_id($category_id)
    {
        $this->set_default_property(self :: PROPERTY_CATEGORY_ID, $category_id);
    }

    /**
     * Returns the content_object_id
     *
     * @return int
     */
    public function get_content_object_id()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENT_OBJECT_ID);
    }

    /**
     * Sets the content_object_id
     *
     * @param int $content_object_id
     */
    public function set_content_object_id($content_object_id)
    {
        $this->set_default_property(self :: PROPERTY_CONTENT_OBJECT_ID, $content_object_id);
    }

    /**
     * Returns the type
     *
     * @return int
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * Sets the type
     *
     * @param int $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
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
}
