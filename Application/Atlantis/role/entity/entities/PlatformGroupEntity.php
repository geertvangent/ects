<?php
namespace Chamilo\Application\Atlantis\role\entity\entities;

use libraries\format\AdvancedElementFinderElementType;
use libraries\platform\translation\Translation;

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
