<?php
namespace application\atlantis;

use common\libraries\Theme;

class SettingsAtlantisConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }

}
