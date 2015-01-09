<?php
namespace application\atlantis;

use libraries\format\theme\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
