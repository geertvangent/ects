<?php
namespace application\atlantis\role\entity;

use libraries\AdvancedElementFinderElementType;
use libraries\Translation;

class PlatformGroupEntity extends \core\rights\PlatformGroupEntity
{

    /**
     * Retrieves the type for the advanced element finder for the simple rights editor
     */
    public function get_element_finder_type()
    {
        return new AdvancedElementFinderElementType(
            'platform_groups', 
            Translation :: get('PlatformGroups'), 
            __NAMESPACE__, 
            'platform_group_entity_feed', 
            array());
    }
}
