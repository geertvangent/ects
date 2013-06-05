<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\DelegateComponent;

class AllUsersComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        header('Content-Type: text/html; charset=utf-8');

        try
        {
            echo '<pre>';
            echo '[USER SYNC STARTED] ' . date('c', time()) . "\n";

            $synchronization = UserSynchronization :: factory('all');
            $synchronization->run();

            echo '[  USER SYNC ENDED] ' . date('c', time()) . "\n";
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
