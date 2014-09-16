<?php
namespace application\atlantis;

use libraries\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
