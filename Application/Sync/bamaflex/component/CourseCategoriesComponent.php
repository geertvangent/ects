<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Component;

use Chamilo\Application\Weblcms\CourseCategory;
use Chamilo\Libraries\Architecture\DelegateComponent;
use Chamilo\Libraries\Platform\PlatformSetting;

class CourseCategoriesComponent extends Manager implements DelegateComponent
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
            Synchronization :: log('Course categories sync started', date('c', time()));
            flush();

            $years = PlatformSetting :: get('academic_year', __NAMESPACE__);
            $years = explode(',', $years);

            $root_group = new CourseCategory();
            $root_group->set_id(0);

            foreach ($years as $year)
            {
                $synchronization = CourseCategorySynchronization :: factory(
                    'academic_year',
                    new DummyCourseCategorySynchronization($root_group, $year));
                $synchronization->run();
            }

            Synchronization :: log('Course categories sync ended', date('c', time()));
            echo '</pre>';
        }
        catch (\Chamilo\Exception $exception)
        {
            echo 'Synchronization failed';
        }
    }
}
