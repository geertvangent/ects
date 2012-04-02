<?php
namespace application\atlantis\role\entity;

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