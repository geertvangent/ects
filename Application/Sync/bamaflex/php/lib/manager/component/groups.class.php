<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\DelegateComponent;

class GroupsComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        try
        {
            echo '<pre>';
            Synchronization :: log('Group sync started', date('c', time()));

            $root_group = \group\DataManager :: get_root_group();

            $synchronization = GroupSynchronization :: factory('academic_year', new DummyGroupSynchronization($root_group));
            $synchronization->run();

            $synchronization = GroupSynchronization :: factory('central_administration', new DummyGroupSynchronization($root_group));
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
