<?php
namespace Ehb\Application\Atlantis\Role\Entity\Ajax\Component;

use Chamilo\Core\Rights\Entity\UserEntity;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElement;

/**
 * Feed to return users from the user entity
 * 
 * @package rights
 * @author Sven Vanpoucke - Hogeschool Gent
 */
class UserEntityFeedComponent extends \Chamilo\Core\User\Ajax\Component\UsersFeedComponent
{

    /**
     * Returns the advanced element finder element for the given user
     * 
     * @param $user User
     *
     * @return AdvancedElementFinderElement
     */
    protected function get_element_for_user($user)
    {
        return new AdvancedElementFinderElement(
            UserEntity::ENTITY_TYPE . '_' . $user->get_id(), 
            'type type_user', 
            $user->get_fullname(), 
            $user->get_official_code());
    }
}
