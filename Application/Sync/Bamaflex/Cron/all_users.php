<?php
namespace Ehb\Application\Sync\Bamaflex\Cron;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\UserSynchronization;

/**
 * This script will load the requested application and launch it.
 */
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
     DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
     'Chamilo/Libraries/Architecture/Bootstrap.php';

$bootstrap = \Chamilo\Libraries\Architecture\Bootstrap :: setup();

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "18000");

    echo '[USER SYNC STARTED] ' . date('c', time()) . "\n";
    flush();

    $synchronization = UserSynchronization :: factory('all');
    $synchronization->run();

    echo '[  USER SYNC ENDED] ' . date('c', time()) . "\n";
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}
