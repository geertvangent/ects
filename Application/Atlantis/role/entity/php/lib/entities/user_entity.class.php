<?php
namespace application\atlantis\role\entity;

use common\libraries\AdvancedElementFinderElementType;
use common\libraries\Translation;

class UserEntity extends \rights\UserEntity
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
