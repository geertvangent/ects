<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\GroupSynchronization;
use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group\DummyGroupSynchronization;
use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Synchronization;
use Chamilo\Application\EhbSync\Bamaflex\Manager;

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
            Synchronization :: log('Group sync started', date('c', time()));
            flush();

            $years = PlatformSetting :: get('academic_year', __NAMESPACE__);
            $years = explode(',', $years);

            $root_group = \Chamilo\Core\Group\Storage\DataManager :: get_root_group();

            foreach ($years as $year)
            {
                $synchronization = GroupSynchronization :: factory(
                    'academic_year',
                    new DummyGroupSynchronization($root_group, $year));
                $synchronization->run();
            }

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
    }
}
