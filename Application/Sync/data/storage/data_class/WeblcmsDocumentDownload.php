<?php
namespace Chamilo\Application\EhbSync\Data\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 * Tracks the visits of a user to a course
 *
 * @package application\ehb_sync\data
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class WeblcmsDocumentDownload extends DataClass
{
    const CLASS_NAME = __CLASS__;

    // Properties
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_COURSE_ID = 'course_id';
    const PROPERTY_TOOL_ID = 'tool_id';
    const PROPERTY_CATEGORY_ID = 'category_id';
    const PROPERTY_PUBLICATION_ID = 'publication_id';
    const PROPERTY_ACCESS_DATE = 'access_date';

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
                self :: PROPERTY_COURSE_ID,
                self :: PROPERTY_TOOL_ID,
                self :: PROPERTY_CATEGORY_ID,
                self :: PROPERTY_PUBLICATION_ID,
                self :: PROPERTY_ACCESS_DATE));
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
     * Returns the course_id
     *
     * @return int
     */
    public function get_course_id()
    {
        return $this->get_default_property(self :: PROPERTY_COURSE_ID);
    }

    /**
     * Sets the course_id
     *
     * @param int $course_id
     */
    public function set_course_id($course_id)
    {
        $this->set_default_property(self :: PROPERTY_COURSE_ID, $course_id);
    }

    /**
     * Returns the tool_id
     *
     * @return int
     */
    public function get_tool_id()
    {
        return $this->get_default_property(self :: PROPERTY_TOOL_ID);
    }

    /**
     * Sets the tool_id
     *
     * @param int $tool_id
     */
    public function set_tool_id($tool_id)
    {
        $this->set_default_property(self :: PROPERTY_TOOL_ID, $tool_id);
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
}
