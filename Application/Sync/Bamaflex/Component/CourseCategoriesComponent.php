<?php
namespace Ehb\Application\Sync\Bamaflex\Component;

use Chamilo\Application\Weblcms\Storage\DataClass\CourseCategory;
use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Bamaflex\Manager;
use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseCategory\DummyCourseCategorySynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseCategorySynchronization;

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
            Synchronization::log('Course categories sync started', date('c', time()));
            flush();

            $years = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'academic_year'));
            $years = explode(',', $years);

            $root_group = new CourseCategory();
            $root_group->set_id(0);

            foreach ($years as $year)
            {
                $synchronization = CourseCategorySynchronization::factory(
                    'academic_year',
                    new DummyCourseCategorySynchronization($root_group, $year));
                $synchronization->run();
            }

            Synchronization::log('Course categories sync ended', date('c', time()));
            echo '</pre>';
        }
        catch (\Exception $exception)
        {
            var_dump($exception);
            echo 'Synchronization failed';
        }
    }
}
