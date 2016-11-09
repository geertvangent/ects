<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Photo;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Photo
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Module extends \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Module
{

    public function get_condition()
    {
        $users = \Chamilo\Application\Weblcms\Course\Storage\DataManager::retrieve_all_course_users(
            $this->get_application()->get_course_id());
        
        $user_ids = array();
        
        while ($user = $users->next_result())
        {
            $user_ids[] = $user[User::PROPERTY_ID];
        }
        
        return new InCondition(new PropertyConditionVariable(User::class_name(), User::PROPERTY_ID), $user_ids);
    }
}
