<?php
namespace application\discovery;

use common\libraries\Theme;

class SettingsConnector
{

    function get_themes()
    {
        return Theme :: get_themes();
    }

}
?>