<?php
namespace application\discovery\module\profile\implementation\chamilo;

use common\libraries\InCondition;
use common\libraries\NotCondition;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\AdvancedElementFinderElementType;
use common\libraries\Translation;

use group\Group;
use group\GroupDataManager;

use rights\PlatformGroupEntity;
use common\libraries\AdvancedElementFinderElement;

/**
 * Extension on the platform group entity specific for the course to limit the platform groups
 *
 * @author Sven Vanpoucke
 */
class RightsPlatformGroupEntity extends PlatformGroupEntity
{
    /**
     * The subscribed group ids for the course
     * @var Array<int>
     */
    private $subscribed_platform_group_ids;
    /**
     * Limits the groups by id
     * @var Array<int>
     */
    private $limited_groups;
    /**
     * Excludes the groups by id
     * @var Array<int>
     */
    private $excluded_groups;
    private $publication_id;
    private static $instance;

    static function get_instance($publication_id)
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self($publication_id);
        }
        return self :: $instance;
    }

    function __construct($publication_id, $subscribed_platform_group_ids = array(), $limited_groups = array(), $excluded_groups = array())
    {
        $this->publication_id = $publication_id;
        $this->limited_groups = $limited_groups;
        $this->excluded_groups = $excluded_groups;
        $this->subscribed_platform_group_ids = $subscribed_platform_group_ids;
    }

    /**
     * Getters and setters
     */
    public function limit_groups($limited_groups)
    {
        $this->limited_groups = $limited_groups;
    }

    public function exclude_groups($excluded_groups)
    {
        $this->excluded_groups = $excluded_groups;
    }

    function get_subscribed_platform_group_ids()
    {
        return $this->subscribed_platform_group_ids;
    }

    function set_subscribed_platform_group_ids($subscribed_platform_group_ids)
    {
        $this->subscribed_platform_group_ids = $subscribed_platform_group_ids;
    }

    /**
     * Builds the condition with the limited and excluded groups
     *
     * @param Condition $condition
     * @return Condition
     */
    public function get_condition(Condition $condition)
    {
        $conditions = array();
        
        if ($this->limited_groups)
        {
            $conditions[] = new InCondition(Group :: PROPERTY_ID, $this->limited_groups, Group :: get_table_name());
        }
        
        if ($this->excluded_groups)
        {
            $conditions[] = new NotCondition(new InCondition(Group :: PROPERTY_ID, $this->excluded_groups, Group :: get_table_name()));
        }
        
        if ($condition)
        {
            $conditions[] = $condition;
        }
        
        $count = count($conditions);
        if ($count > 1)
        {
            return new AndCondition($conditions);
        }
        
        if ($count == 1)
        {
            return $conditions[0];
        }
    }

    /**
     * Override the get root ids to only return the subscribed groups instead of the chamilo root group
     * @return Array<int>
     */
    function get_root_ids()
    {
        if (! empty($this->subscribed_platform_group_ids))
        {
            return $this->subscribed_platform_group_ids;
        }
        
        return parent :: get_root_ids();
    }

    /**
     * Retrieves the entity item ids relevant for a given user.
     * Overrides because only subscribed platformgroups need to be checked. Also none of their parents as they are not
     * subscribed in the course, and therefore cannot have specific rights set to them
     *
     * @param integer $user_id
     * @return array
     */
    function retrieve_entity_item_ids_linked_to_user($user_id)
    {
        if (is_null($this->platform_group_cache[$user_id]))
        {
            $gdm = GroupDataManager :: get_instance();
            $this->platform_group_cache[$user_id] = $gdm->retrieve_all_subscribed_groups_array($user_id, true);
        }
        return $this->platform_group_cache[$user_id];
    }

    /**
     * Retrieves the type for the advanced element finder for the simple rights editor
     */
    function get_element_finder_type()
    {
        return new AdvancedElementFinderElementType('platform_groups', Translation :: get('PublicationPlatformGroups'), __NAMESPACE__, 'publication_platform_groups_feed', array(
                'publication_id' => $this->publication_id));
    }

}

?>
