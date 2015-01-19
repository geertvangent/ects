<?php
namespace Chamilo\Application\Atlantis\Resources\Settings;
use Chamilo\Libraries\Format\Theme\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme :: get_themes();
    }
}
