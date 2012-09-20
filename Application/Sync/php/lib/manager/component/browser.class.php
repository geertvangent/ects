<?php
namespace application\ehb_sync;

use common\libraries\NotAllowedException;

use common\libraries\Utilities;

use common\libraries\Theme;

use common\libraries\Translation;

use common\libraries\DelegateComponent;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
            
        $this->display_header();
        $types = array(self :: ACTION_BAMAFLEX, self :: ACTION_ATLANTIS);
        
        $html = array();
        foreach ($types as $type)
        {
            
            $html[] = '<a href="' . $this->get_url(array(self :: PARAM_ACTION => $type)) . '">';
            $html[] = '<div class="create_block" style="background-image: url(' . Theme :: get_image_path() . 'component/' . $type . '.png);">';
            $html[] = Translation :: get(Utilities :: underscores_to_camelcase($type) . 'Component');
            $html[] = '</div>';
            $html[] = '</a>';
        }
        echo implode("\n", $html);
        $this->display_footer();
    }

}
?>