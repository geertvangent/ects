<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Core\User\Storage\DataClass\User;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Password extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_PASSWORD = 'password';

    private $user;

    public static function get_default_property_names($extended_property_names = array())
    {
        return parent :: get_default_property_names(array(self :: PROPERTY_USER_ID, self :: PROPERTY_PASSWORD));
    }

    public function get_data_manager()
    {
    }

    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    public function set_user_id($user)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user);
    }

    public function get_user()
    {
        if (! isset($this->user))
        {
            $this->user = \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(
                User :: class_name(),
                (int) $this->get_user_id());
        }

        return $this->user;
    }

    public function set_user(User $user)
    {
        $this->user = $user;
    }

    public function get_password()
    {
        return $this->get_default_property(self :: PROPERTY_PASSWORD);
    }

    public function set_password($user)
    {
        $this->set_default_property(self :: PROPERTY_PASSWORD, $user);
    }
}
