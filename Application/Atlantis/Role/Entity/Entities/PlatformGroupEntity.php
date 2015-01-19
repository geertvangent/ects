<?php
namespace Chamilo\Application\Atlantis\Role\Entity\Entities;

use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElementType;
use Chamilo\Libraries\Platform\Translation;

class PlatformGroupEntity extends \Chamilo\Core\Rights\Entity\PlatformGroupEntity
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
