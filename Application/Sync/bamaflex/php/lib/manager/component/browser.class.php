<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\DelegateComponent;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        $this->display_header();
        
        $this->display_footer();
    }

}
?>