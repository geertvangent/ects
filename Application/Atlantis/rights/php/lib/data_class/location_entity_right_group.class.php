<?php
namespace application\atlantis\rights;

use rights\RightsDataManager;
use group\GroupDataManager;
use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 *
 * @author Hans De Bisschop
 */
class LocationEntityRightGroup extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Request properties
     */
    const PROPERTY_LOCATION_ENTITY_RIGHT_ID = 'location_entity_right_id';
    const PROPERTY_GROUP_ID = 'group_id';

    /**
     * The group of the LocationEntityRightGroup
     * 
     * @var \group\Group
     */
    private $group;

    /**
     *
     * @var LocationEntityRightGroup
     */
    private $location_entity_right;

    /**
     * Get the default properties
     * 
     * @param $extended_property_names multitype:string
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_LOCATION_ENTITY_RIGHT_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP_ID;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    public function get_location_entity_right_id()
    {
        return $this->get_default_property(self :: PROPERTY_LOCATION_ENTITY_RIGHT_ID);
    }

    public function get_location_entity_right()
    {
        if (! isset($this->location_entity_right))
        {
            $this->location_entity_right = RightsDataManager :: get_instance()->retrieve_rights_location_entity_right_by_id(
                'repository_quota', 
                $this->get_location_entity_right_id());
        }
        return $this->location_entity_right;
    }

    public function set_location_entity_right_id($location_entity_right_id)
    {
        $this->set_default_property(self :: PROPERTY_LOCATION_ENTITY_RIGHT_ID, $location_entity_right_id);
    }

    public function get_group_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_ID);
    }

    public function get_group()
    {
        if (! isset($this->group))
        {
            $this->group = GroupDataManager :: get_instance()->retrieve_group($this->get_group_id());
        }
        return $this->group;
    }

    public function set_group_id($group_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_ID, $group_id);
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
