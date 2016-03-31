<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Ehb\Application\Avilarts\Course\Storage\DataManager as CourseDataManager;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\UserExporter\CourseGroupUserExportExtender;
use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Ehb\Application\Avilarts\Tool\Implementation\User\UserExporter\CourseUserExportExtender;
use Ehb\Application\Avilarts\UserExporter\Renderer\ExcelUserExportRenderer;
use Ehb\Application\Avilarts\UserExporter\UserExporter;

/**
 * Exports the user list
 * 
 * @author Sven Vanpoucke - Hogeschool Gent
 */
class ExporterComponent extends Manager
{

    public function run()
    {
        $user_records = CourseDataManager :: retrieve_all_course_users($this->get_course_id());
        
        $users = array();
        
        while ($user_record = $user_records->next_result())
        {
            $users[] = DataClass :: factory(User :: class_name(), $user_record);
        }
        
        $exporter = new UserExporter(
            new ExcelUserExportRenderer(), 
            array(
                new CourseUserExportExtender($this->get_course_id()), 
                new CourseGroupUserExportExtender($this->get_course_id())));
        
        $file_path = $exporter->export($users);
        
        Filesystem :: file_send_for_download(
            $file_path, 
            true, 
            'export_users_' . $this->get_course_id() . '.xlsx', 
            'application/vnd.openxmlformats');
        
        Filesystem :: remove($file_path);
    }
}
