<?php
namespace Ehb\Application\Sync\Cas\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Sync\Cas\Manager;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->display_header();

        $types = array(self :: ACTION_ALL_USERS, self :: ACTION_STATISTICS);

        $html = array();
        foreach ($types as $type)
        {

            $html[] = '<a href="' . $this->get_url(array(self :: PARAM_ACTION => $type)) . '">';
            $html[] = '<div class="create_block" style="background-image: url(' . Theme :: getInstance()->getImagePath('Ehb\Application\Sync\Cas', true) .
                 'Component/' . $type . '.png);">';
            $html[] = Translation :: get(
                StringUtilities :: getInstance()->createString($type)->upperCamelize() . 'Component');
            $html[] = '</div>';
            $html[] = '</a>';
        }
        echo implode(PHP_EOL, $html);

        $this->display_footer();
    }
}
