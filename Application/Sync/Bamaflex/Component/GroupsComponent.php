<?php
namespace Ehb\Application\Sync\Bamaflex\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Ehb\Application\Sync\Bamaflex\Manager;
use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\DummyGroupSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

class GroupsComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "0");
        header('Content-Type: text/html; charset=utf-8');

        try
        {
            echo '<pre>';
            Synchronization::log('Group sync started', date('c', time()));
            flush();

            $years = PlatformSetting::get('academic_year', 'Ehb\Application\Sync');
            $years = explode(',', $years);

            $root_group = \Chamilo\Core\Group\Storage\DataManager::get_root_group();

            // foreach ($years as $year)
            // {
            // $synchronization = GroupSynchronization::factory(
            // 'academic_year',
            // new DummyGroupSynchronization($root_group, $year));
            // $synchronization->run();
            // }

            $synchronization = GroupSynchronization::factory(
                'current',
                new DummyGroupSynchronization($root_group, $years));
            $synchronization->run();

            Synchronization::log('Group sync ended', date('c', time()));
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
