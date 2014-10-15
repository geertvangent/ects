<?php
namespace application\atlantis;

use libraries\format\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
