<?php
namespace Chamilo\Application\Discovery\Module\Profile\Implementation\Chamilo;

use Chamilo\Libraries\Platform\Translation;

class Module extends \Chamilo\Application\Discovery\Module\Profile\Module
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
