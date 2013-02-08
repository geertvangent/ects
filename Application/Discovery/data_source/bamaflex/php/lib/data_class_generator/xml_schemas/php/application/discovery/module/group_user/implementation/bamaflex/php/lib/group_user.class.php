<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;
use common\libraries\Utilities;

/**
 * application.discovery.module.group_user.implementation.bamaflex
 *
 * @author Magali Gillard
 */
class GroupUser extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;

    /**
     *
     * @var string
     */
    const PROPERTY_SOURCE = 'source';
    /**
     *
     * @var integer
     */
    const PROPERTY_ENROLLMENT_ID = 'enrollment_id';
    /**
     *
     * @var integer
     */
    const PROPERTY_PERSON_ID = 'person_id';
    /**
     *
     * @var string
     */
    const PROPERTY_LAST_NAME = 'last_name';
    /**
     *
     * @var string
     */
    const PROPERTY_FIRST_NAME = 'first_name';
    /**
     *
     * @var integer
     */
    const PROPERTY_GROUP_CLASS_ID = 'group_class_id';
    /**
     *
     * @var string
     */
    const PROPERTY_YEAR = 'year';
    /**
     *
     * @var integer
     */
    const PROPERTY_STRUCK = 'struck';

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_LAST_NAME;
        $extended_property_names[] = self :: PROPERTY_FIRST_NAME;
        $extended_property_names[] = self :: PROPERTY_GROUP_CLASS_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_STRUCK;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the source of this GroupUser.
     *
     * @return string The source.
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this GroupUser.
     *
     * @param string $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the enrollment_id of this GroupUser.
     *
     * @return integer The enrollment_id.
     */
    function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment_id of this GroupUser.
     *
     * @param integer $enrollment_id
     */
    function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the person_id of this GroupUser.
     *
     * @return integer The person_id.
     */
    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this GroupUser.
     *
     * @param integer $person_id
     */
    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the last_name of this GroupUser.
     *
     * @return string The last_name.
     */
    function get_last_name()
    {
        return $this->get_default_property(self :: PROPERTY_LAST_NAME);
    }

    /**
     * Sets the last_name of this GroupUser.
     *
     * @param string $last_name
     */
    function set_last_name($last_name)
    {
        $this->set_default_property(self :: PROPERTY_LAST_NAME, $last_name);
    }

    /**
     * Returns the first_name of this GroupUser.
     *
     * @return string The first_name.
     */
    function get_first_name()
    {
        return $this->get_default_property(self :: PROPERTY_FIRST_NAME);
    }

    /**
     * Sets the first_name of this GroupUser.
     *
     * @param string $first_name
     */
    function set_first_name($first_name)
    {
        $this->set_default_property(self :: PROPERTY_FIRST_NAME, $first_name);
    }

    /**
     * Returns the group_class_id of this GroupUser.
     *
     * @return integer The group_class_id.
     */
    function get_group_class_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_CLASS_ID);
    }

    /**
     * Sets the group_class_id of this GroupUser.
     *
     * @param integer $group_class_id
     */
    function set_group_class_id($group_class_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_CLASS_ID, $group_class_id);
    }

    /**
     * Returns the year of this GroupUser.
     *
     * @return string The year.
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this GroupUser.
     *
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the struck of this GroupUser.
     *
     * @return integer The struck.
     */
    function get_struck()
    {
        return $this->get_default_property(self :: PROPERTY_STRUCK);
    }

    /**
     * Sets the struck of this GroupUser.
     *
     * @param integer $struck
     */
    function set_struck($struck)
    {
        $this->set_default_property(self :: PROPERTY_STRUCK, $struck);
    }

    /**
     *
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
