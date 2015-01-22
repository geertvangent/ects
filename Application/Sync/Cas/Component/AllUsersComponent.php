<?php
namespace Ehb\Application\Sync\Cas\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Cas\Manager;
use Ehb\Application\Sync\Cas\Synchronization\Type\UserSynchronization;

class AllUsersComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
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
