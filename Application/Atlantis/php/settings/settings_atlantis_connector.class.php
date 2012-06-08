<?php
namespace application\atlantis;

use common\libraries\Theme;

class SettingsAtlantisConnector
{

    function get_themes()
    {
        return Theme :: get_themes();
    }

}
?>