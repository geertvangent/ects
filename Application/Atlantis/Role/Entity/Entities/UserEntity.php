<?php
namespace Ehb\Application\Atlantis\Role\Entity\Entities;

use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElementType;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\Role\Entity\Manager;

class UserEntity extends \Chamilo\Core\Rights\Entity\UserEntity
{

    /**
     * Retrieves the type for the advanced element finder for the simple rights editor
     */
    public function get_element_finder_type()
    {
        return new AdvancedElementFinderElementType(
            'users', 
            Translation::get('Users'), 
            Manager::context() . '\Ajax', 
            'user_entity_feed', 
            array());
    }
}
