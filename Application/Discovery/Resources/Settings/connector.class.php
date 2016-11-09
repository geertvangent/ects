<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Format\Theme;

/**
 *
 * @package Chamilo\Application\Discovery
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class SettingsConnector
{

    public function get_themes()
    {
        return Theme::getInstance()->getAvailableThemes();
    }
}
