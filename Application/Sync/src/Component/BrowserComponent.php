<?php
namespace Chamilo\Application\EhbSync\Component;

use Chamilo\Libraries\Architecture\NotAllowedException;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Architecture\DelegateComponent;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        $this->display_header();

        $types = array(self :: ACTION_BAMAFLEX, self :: ACTION_ATLANTIS, self :: ACTION_CAS, self :: ACTION_DATA);

        $html = array();
        foreach ($types as $type)
        {
            $html[] = '<a href="' . $this->get_url(array(self :: PARAM_ACTION => $type)) . '">';
            $html[] = '<div class="create_block" style="background-image: url(' . Theme :: get_image_path() .
                 'component/' . $type . '.png);">';
            $html[] = Translation :: get(Utilities :: underscores_to_camelcase($type) . 'Component');
            $html[] = '</div>';
            $html[] = '</a>';
        }

        echo implode("\n", $html);

        $this->display_footer();
    }
}
