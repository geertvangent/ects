<?php
namespace Chamilo\Application\EhbSync\Atlantis\Cron;

/**
 * This script will load the requested application and launch it.
 */
require_once dirname(__FILE__) . '/../../../../../common/common.inc.php';

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "18000");
    echo '<pre>';
    echo '[DISCOVERY SYNC STARTED] ' . date('c', time()) . "\n";
    $synchronization = new DiscoverySynchronization();
    $synchronization->run();
    echo '[  DISCOVERY SYNC ENDED] ' . date('c', time()) . "\n";
    echo '</pre>';
}
catch (\Chamilo\Exception $exception)
{
    echo 'Synchronization failed';
}
