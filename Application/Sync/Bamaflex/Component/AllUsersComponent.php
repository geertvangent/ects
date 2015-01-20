<?php
namespace Ehb\Application\Sync\Bamaflex\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\UserSynchronization;
use Ehb\Application\Sync\Bamaflex\Manager;

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
            flush();

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
