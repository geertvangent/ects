<?php
namespace application\discovery;

use common\libraries\Theme;

class SettingsDiscoveryConnector
{

    function get_themes()
    {
        return Theme :: get_themes();
    }

}
?>