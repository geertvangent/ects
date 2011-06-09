<?php
namespace application\discovery\module\enrollment\implementation\chamilo;

use common\libraries\Translation;

use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\enrollment\Module
{

    /* (non-PHPdoc)
     * @see application\discovery\module\enrollment.Module::get_general_properties()
     */
    function get_general_properties()
    {
        $properties = parent :: get_general_properties();
        $properties[Translation :: get('Username')] = $this->get_enrollment()->get_username();
        $properties[Translation :: get('Timezone')] = $this->get_enrollment()->get_timezone();

        return $properties;
    }
}
?>