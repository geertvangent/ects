<?php
namespace application\ehb_sync\bamaflex;

use libraries\DelegateComponent;

class CoursesComponent extends Manager implements DelegateComponent
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
            Synchronization :: log('Courses sync started', date('c', time()));
            flush();

            $synchronization = new CourseSynchronization();
            $synchronization->run();

            Synchronization :: log('Courses sync ended', date('c', time()));
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
            Synchronization :: log('Synchronization failed', date('c', time()));
            Synchronization :: log('Courses sync ended', date('c', time()));
        }
    }
}
