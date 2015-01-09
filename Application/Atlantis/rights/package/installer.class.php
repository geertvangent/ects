<?php
namespace application\atlantis\rights;

use libraries\platform\translation\Translation;

class Installer extends \configuration\package\Installer
{

    public function extra()
    {
        if (! Rights :: get_instance()->create_access_root())
        {
            return false;
        }
        else
        {
            $this->add_message(self :: TYPE_NORMAL, Translation :: get('AccessLocationCreated'));
        }

        return true;
    }
}
