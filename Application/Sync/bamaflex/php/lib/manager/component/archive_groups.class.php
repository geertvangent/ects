<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\DelegateComponent;

class ArchiveGroupsComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        try
        {
            echo '<pre>';
            Synchronization :: log('Group sync started', date('c', time()));

            $root_group = \group\DataManager :: get_root_group();

            $synchronization = ArchiveGroupSynchronization :: factory('archive_academic_year', new ArchiveDummyGroupSynchronization($root_group));
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
