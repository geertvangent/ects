<?php
namespace application\atlantis;

use common\libraries\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
