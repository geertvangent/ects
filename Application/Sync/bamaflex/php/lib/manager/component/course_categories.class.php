<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\CourseCategory;

use common\libraries\Theme;

use common\libraries\Translation;

use common\libraries\Utilities;

use common\libraries\DelegateComponent;

class CourseCategoriesComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        try
        {
            echo '<pre>';
            Synchronization :: log('Course categories sync started', date('c', time()));
            
            $root_group = new CourseCategory();
            $root_group->set_id(0);
            
            $synchronization = CourseCategorySynchronization :: factory('academic_year', new DummyCourseCategorySynchronization($root_group));
            $synchronization->run();
            
            Synchronization :: log('Course categories sync ended', date('c', time()));
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }

}
?>