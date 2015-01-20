<?php
namespace Ehb\Application\Apple\Component;

use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Apple\Manager;

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
