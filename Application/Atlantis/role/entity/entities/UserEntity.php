<?php
namespace Chamilo\Application\Atlantis\role\entity\entities;

use libraries\format\AdvancedElementFinderElementType;
use libraries\platform\translation\Translation;

class UserEntity extends \core\rights\UserEntity
{

    /**
     * Retrieves the type for the advanced element finder for the simple rights editor
     */
    public function get_element_finder_type()
    {
        return new AdvancedElementFinderElementType(
            'users', 
            Translation :: get('Users'), 
            __NAMESPACE__, 
            'user_entity_feed', 
            array());
    }
}
