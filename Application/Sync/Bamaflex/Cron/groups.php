<?php
namespace Ehb\Application\Sync\Bamaflex\Cron;

use Chamilo\Configuration\Configuration;
use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\DummyGroupSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 * This script will load the requested application and launch it.
 */
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
     DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
     'Chamilo/Libraries/Architecture/Bootstrap.php';

$bootstrap = \Chamilo\Libraries\Architecture\Bootstrap::setup();

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "0");
    echo '<pre>';
    Synchronization::log('Group sync started', date('c', time()));
    flush();
    
    $years = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'academic_year'));
    $years = explode(',', $years);
    
    $root_group = \Chamilo\Core\Group\Storage\DataManager::get_root_group();
    
    foreach ($years as $year)
    {
        $synchronization = GroupSynchronization::factory(
            'academic_year', 
            new DummyGroupSynchronization($root_group, $year));
        $synchronization->run();
    }
    
    $synchronization = GroupSynchronization::factory('current', new DummyGroupSynchronization($root_group, $years));
    $synchronization->run();
    
    Synchronization::log('Group sync ended', date('c', time()));
    echo '</pre>';
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}
