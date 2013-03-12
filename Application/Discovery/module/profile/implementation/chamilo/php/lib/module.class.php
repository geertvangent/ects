<?php
namespace application\discovery\module\profile\implementation\chamilo;

use common\libraries\Translation;

class Module extends \application\discovery\module\profile\Module
{

    /*
     * (non-PHPdoc) @see application\discovery\module\profile.Module::get_general_properties()
     */
    public function get_general_properties()
    {
        $properties = parent :: get_general_properties();
        $properties[Translation :: get('Username')] = $this->get_profile()->get_username();
        $properties[Translation :: get('Timezone')] = $this->get_profile()->get_timezone();

        return $properties;
    }
}
