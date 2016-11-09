<?php
namespace Ehb\Application\Sync\Bamaflex\Cron;

use Chamilo\Application\Weblcms\Storage\DataClass\CourseCategory;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\AdminSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseCategory\DummyCourseCategorySynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseCategorySynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\CourseSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\DummyGroupSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\UserSynchronization;

/**
 * This script will load the requested application and launch it.
 */
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
     DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
     'Chamilo/Libraries/Architecture/Bootstrap.php';

$bootstrap = \Chamilo\Libraries\Architecture\Bootstrap::setup();

function synchronizeUsers()
{
    // User synchronization
    echo '[USER SYNC STARTED] ' . date('c', time()) . "\n";
    flush();
    
    $synchronization = UserSynchronization::factory('all');
    $synchronization->run();
    
    echo '[  USER SYNC ENDED] ' . date('c', time()) . "\n";
}

function synchronizeGroups()
{
    // Group synchronization
    Synchronization::log('Group sync started', date('c', time()));
    flush();
    
    $years = PlatformSetting::get('academic_year', 'Ehb\Application\Sync');
    $years = explode(',', $years);
    
    $root_group = \Chamilo\Core\Group\Storage\DataManager::get_root_group();
    
    foreach ($years as $year)
    {
        $synchronization = GroupSynchronization::factory(
            'academic_year', 
            new DummyGroupSynchronization($root_group, $year));
        $synchronization->run();
    }
    
    $synchronization = GroupSynchronization::factory(
        'central_administration', 
        new DummyGroupSynchronization($root_group));
    $synchronization->run();
    
    Synchronization::log('Group sync ended', date('c', time()));
}

function synchronizeAdmins()
{
    // Admins synchronization
    Synchronization::log('Admins sync started', date('c', time()));
    flush();
    
    $synchronization = new AdminSynchronization();
    $synchronization->run();
    
    Synchronization::log('Admins sync ended', date('c', time()));
}

function synchronizeCourseCategories()
{
    Synchronization::log('Course categories sync started', date('c', time()));
    flush();
    
    $years = PlatformSetting::get('academic_year', 'Ehb\Application\Sync');
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
}

function synchronizeCourses()
{
    // Course synchronization
    Synchronization::log('Courses sync started', date('c', time()));
    flush();
    
    $synchronization = new CourseSynchronization();
    $synchronization->run();
    
    Synchronization::log('Courses sync ended', date('c', time()));
}

try
{
    ini_set("memory_limit", "-1");
    ini_set("max_execution_time", "18000");
    
    synchronizeUsers();
    synchronizeGroups();
    synchronizeAdmins();
    synchronizeCourseCategories();
    synchronizeCourses();
}
catch (\Exception $exception)
{
    echo 'Synchronization failed';
}