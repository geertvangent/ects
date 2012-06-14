<?php
namespace application\ehb_sync;

use common\libraries\DelegateComponent;

class BamaflexComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        \application\ehb_sync\bamaflex\Manager :: launch($this);
    }

}
?>