<?php
namespace application\ehb_sync\atlantis;

use common\libraries\DelegateComponent;

class DiscoveryComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
//         try
//         {
            echo '<pre>';
            echo '[USER SYNC STARTED] ' . date('c', time()) . "\n";
            $synchronization = new DiscoverySynchronization();
            $synchronization->run();
            echo '[  USER SYNC ENDED] ' . date('c', time()) . "\n";
            echo '</pre>';
//         }
//         catch (\Exception $exception)
//         {
//             echo 'Synchronization failed';
//         }
    }
}
