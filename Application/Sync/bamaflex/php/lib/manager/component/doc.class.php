<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\DelegateComponent;

class DocComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        try
        {
            $synchronization = UserSynchronization :: factory('doc');
            $synchronization->run();
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }

}
