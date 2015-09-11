<?php
namespace Ehb\Application\Sync\Bamaflex\Cron;

use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseSynchronization;

/**
 * This script will load the requested application and launch it.
 */
require_once dirname(__FILE__) . '/../../../../../common/common.inc.php';

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "18000");

    Synchronization :: log('Courses sync started', date('c', time()));
    flush();

    $synchronization = new CourseSynchronization();
    $synchronization->run();

    Synchronization :: log('Courses sync ended', date('c', time()));
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}
