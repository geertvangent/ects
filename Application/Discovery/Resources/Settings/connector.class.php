<?php
namespace Chamilo\Application\Discovery;

use Chamilo\Libraries\Format\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
