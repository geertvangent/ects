<?php
namespace Chamilo\Application\EhbSync\Cas\Component;

use Chamilo\Libraries\Architecture\DelegateComponent;

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
        catch (\Chamilo\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
