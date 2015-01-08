<?php
namespace application\discovery;

use libraries\format\theme\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
