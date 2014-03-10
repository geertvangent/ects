<?php
namespace application\discovery;

use common\libraries\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
