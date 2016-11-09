<?php
namespace Ehb\Application\Atlantis\Resources\Settings;

use Chamilo\Libraries\Format\Theme;

class SettingsConnector
{

    public function get_themes()
    {
        return Theme::getInstance()->getAvailableThemes();
    }
}
