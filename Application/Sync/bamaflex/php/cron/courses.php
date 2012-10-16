<?php
namespace application\ehb_sync\bamaflex;

/**
 * This script will load the requested application and launch it.
 */
use common\libraries\Display;
use Exception;

require_once dirname(__FILE__) . '/../../../../../common/common.inc.php';

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "18000");
    echo '<pre>';
    Synchronization :: log('Courses sync started', date('c', time()));
    
    $synchronization = new CourseSynchronization();
    $synchronization->run();
    
    Synchronization :: log('Courses sync ended', date('c', time()));
    echo '</pre>';
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}

?>