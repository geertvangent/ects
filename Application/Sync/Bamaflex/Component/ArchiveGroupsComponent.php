<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Component;

use Chamilo\Libraries\Architecture\DelegateComponent;

class ArchiveGroupsComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        header('Content-Type: text/html; charset=utf-8');

        try
        {
            echo '<pre>';
            Synchronization :: log('Group sync started', date('c', time()));
            flush();

            $root_group = \Chamilo\Core\Group\DataManager :: get_root_group();

            $synchronization = ArchiveGroupSynchronization :: factory(
                'archive_academic_year',
                new ArchiveDummyGroupSynchronization($root_group));
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
