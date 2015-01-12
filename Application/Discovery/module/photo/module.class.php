<?php
namespace Application\Discovery\module\photo;

use libraries\file\Path;
use libraries\file\Filesystem;
use application\discovery\instance\Instance;
use libraries\platform\Request;

class Module extends \application\discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';
    const PARAM_FACULTY_ID = 'faculty_id';
    const PARAM_PROGRAMME_ID = 'programme_id';
    const PARAM_TYPE = 'type';
    const TYPE_TEACHER = 1;
    const TYPE_STUDENT = 2;
    const TYPE_EMPLOYEE = 3;

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
    {
        $faculty_id = Request :: get(self :: PARAM_FACULTY_ID);
        $training_id = Request :: get(self :: PARAM_TRAINING_ID);
        $programme_id = Request :: get(self :: PARAM_PROGRAMME_ID);
        $type = Request :: get(self :: PARAM_TYPE);
        
        return new Parameters($faculty_id, $training_id, $programme_id, $type);
    }

    public function get_type()
    {
        return Instance :: TYPE_DETAILS;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', 
            Filesystem :: LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }

    public function get_users()
    {
        return array();
    }
}
