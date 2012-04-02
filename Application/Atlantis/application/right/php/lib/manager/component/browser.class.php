<?php
namespace application\atlantis\application\right;

class BrowserComponent extends Manager
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