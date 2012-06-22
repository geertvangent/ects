<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\CourseCategory;

use common\libraries\Theme;

use common\libraries\Translation;

use common\libraries\Utilities;

use common\libraries\DelegateComponent;

class CoursesComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        try
        {
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
    }

}
?>