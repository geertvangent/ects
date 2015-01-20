<?php
namespace Ehb\Application\Sync\Cas\Component;

use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
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
            $html[] = '<div class="create_block" style="background-image: url(' . Theme :: getInstance()->getImagePath() .
                 'component/' . $type . '.png);">';
            $html[] = Translation :: get(Utilities :: underscores_to_camelcase($type) . 'Component');
            $html[] = '</div>';
            $html[] = '</a>';
        }
        echo implode("\n", $html);

        $this->display_footer();
    }
}
