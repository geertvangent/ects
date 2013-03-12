<?php
namespace application\discovery\module\group_user\implementation\bamaflex;


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
    const PROPERTY_TYPE = 'type';

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_LAST_NAME;
        $extended_property_names[] = self :: PROPERTY_FIRST_NAME;
        $extended_property_names[] = self :: PROPERTY_GROUP_CLASS_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_STRUCK;
        $extended_property_names[] = self :: PROPERTY_TYPE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
//         return DataManager :: get_instance();
    }

    /**
     * Returns the source of this GroupUser.
     *
     * @return string The source.
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this GroupUser.
     *
     * @param string $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the enrollment_id of this GroupUser.
     *
     * @return integer The enrollment_id.
     */
    public function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment_id of this GroupUser.
     *
     * @param integer $enrollment_id
     */
    public function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the person_id of this GroupUser.
     *
     * @return integer The person_id.
     */
    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this GroupUser.
     *
     * @param integer $person_id
     */
    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the last_name of this GroupUser.
     *
     * @return string The last_name.
     */
    public function get_last_name()
    {
        return $this->get_default_property(self :: PROPERTY_LAST_NAME);
    }

    /**
     * Sets the last_name of this GroupUser.
     *
     * @param string $last_name
     */
    public function set_last_name($last_name)
    {
        $this->set_default_property(self :: PROPERTY_LAST_NAME, $last_name);
    }

    /**
     * Returns the first_name of this GroupUser.
     *
     * @return string The first_name.
     */
    public function get_first_name()
    {
        return $this->get_default_property(self :: PROPERTY_FIRST_NAME);
    }

    /**
     * Sets the first_name of this GroupUser.
     *
     * @param string $first_name
     */
    public function set_first_name($first_name)
    {
        $this->set_default_property(self :: PROPERTY_FIRST_NAME, $first_name);
    }

    /**
     * Returns the group_class_id of this GroupUser.
     *
     * @return integer The group_class_id.
     */
    public function get_group_class_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_CLASS_ID);
    }

    /**
     * Sets the group_class_id of this GroupUser.
     *
     * @param integer $group_class_id
     */
    public function set_group_class_id($group_class_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_CLASS_ID, $group_class_id);
    }

    /**
     * Returns the year of this GroupUser.
     *
     * @return string The year.
     */
    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this GroupUser.
     *
     * @param string $year
     */
    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the struck of this GroupUser.
     *
     * @return integer The struck.
     */
    public function get_struck()
    {
        return $this->get_default_property(self :: PROPERTY_STRUCK);
    }

    /**
     * Sets the struck of this GroupUser.
     *
     * @param integer $struck
     */
    public function set_struck($struck)
    {
        $this->set_default_property(self :: PROPERTY_STRUCK, $struck);
    }

    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
