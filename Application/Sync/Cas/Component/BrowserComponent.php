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
        $types = array(self :: ACTION_ALL_USERS, self :: ACTION_STATISTICS);

        $html = array();

        $html[] = $this->render_header();

        foreach ($types as $type)
        {

            $html[] = '<a href="' . $this->get_url(array(self :: PARAM_ACTION => $type)) . '">';
            $html[] = '<div class="create_block" style="background-image: url(' .
                 Theme :: getInstance()->getImagesPath('Ehb\Application\Sync\Cas', true) . 'Component/' . $type .
                 '.png);">';
            $html[] = Translation :: get(
                StringUtilities :: getInstance()->createString($type)->upperCamelize() . 'Component');
            $html[] = '</div>';
            $html[] = '</a>';
        }

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }
}
