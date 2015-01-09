<?php
namespace Chamilo\Application\Atlantis\Role\Entity\Entities;

use Chamilo\Libraries\Format\AdvancedElementFinderElementType;
use Chamilo\Libraries\Platform\Translation\Translation;

class UserEntity extends \Chamilo\Core\Rights\UserEntity
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
