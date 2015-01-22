<?php
namespace Ehb\Application\Sync\Bamaflex\Cron;

use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\DummyGroupSynchronization;

/**
 * This script will load the requested application and launch it.
 */
require_once dirname(__FILE__) . '/../../../../../common/common.inc.php';

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "0");
    echo '<pre>';
    Synchronization :: log('Group sync started', date('c', time()));
    flush();

    $root_group = \Chamilo\Core\Group\Storage\DataManager :: get_root_group();

    $synchronization = GroupSynchronization :: factory('academic_year', new DummyGroupSynchronization($root_group));
    $synchronization->run();

    $synchronization = GroupSynchronization :: factory(
        'central_administration',
        new DummyGroupSynchronization($root_group));
    $synchronization->run();

    Synchronization :: log('Group sync ended', date('c', time()));
    echo '</pre>';
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}
