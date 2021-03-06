<?php
namespace Ehb\Application\Sync\Cas\Cron;

/**
 * This script will load the requested application and launch it.
 */
use Ehb\Application\Sync\Cas\Synchronization\Type\UserSynchronization;
use Exception;

require_once dirname(__FILE__) . '/../../../../../common/common.inc.php';

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "18000");
    echo '<pre>';
    echo '[USER SYNC STARTED] ' . date('c', time()) . "\n";
    
    $synchronization = UserSynchronization::factory('all');
    $synchronization->run();
    
    echo '[  USER SYNC ENDED] ' . date('c', time()) . "\n";
    echo '</pre>';
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}
