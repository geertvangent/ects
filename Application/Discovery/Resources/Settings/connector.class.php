<?php
namespace Chamilo\Application\Discovery;

use Chamilo\Libraries\Format\Theme\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
