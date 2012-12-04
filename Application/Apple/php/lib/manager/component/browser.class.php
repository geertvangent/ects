<?php
namespace application\ehb_apple;

use common\libraries\PlatformSetting;

class BrowserComponent extends Manager
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
       include_once 'apple.htm';
    }
}
?>