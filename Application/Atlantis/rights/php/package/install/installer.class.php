<?php
namespace application\atlantis\rights;

use common\libraries\Translation;

class Installer extends \common\libraries\package\Installer
{

    /**
     * Constructor
     */
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    public function install_extra()
    {
        if (! Rights :: get_instance()->create_quota_root())
        {
            return false;
        }
        else
        {
            $this->add_message(self :: TYPE_NORMAL, Translation :: get('QuotaLocationCreated'));
        }
        
        return true;
    }
}
