<?php
namespace Ehb\Application\Atlantis\Rights\Package;

use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\Rights\Rights;

class Installer extends \Chamilo\Configuration\Package\Installer
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
