<?php
namespace application\ehb_apple;

use libraries\Display;
use libraries\DelegateComponent;

class BrowserComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        if (strpos($this->get_user()->get_username(), '@ehb.be') !== false ||
             strpos($this->get_user()->get_username(), '@student.ehb.be') !== false)
        {
            include_once 'apple.htm';
        }
        else
        {
            Display :: not_allowed(null, false);
        }
    }
}
