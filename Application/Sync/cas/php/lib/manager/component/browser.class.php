<?php
namespace application\ehb_sync\cas;

use libraries\Theme;
use libraries\Translation;
use libraries\Utilities;
use libraries\DelegateComponent;

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
