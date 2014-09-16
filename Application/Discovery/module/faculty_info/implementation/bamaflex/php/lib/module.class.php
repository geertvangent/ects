<?php
namespace application\discovery\module\faculty_info\implementation\bamaflex;

use libraries\Request;

class Module extends \application\discovery\module\faculty_info\Module
{
    const PARAM_SOURCE = 'source';

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
    {
        $faculty = Request :: get(self :: PARAM_FACULTY_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new Parameters();
        if ($faculty)
        {
            $parameter->set_faculty_id($faculty);
        }
        
        if ($source)
        {
            $parameter->set_source($source);
        }
        return $parameter;
    }
}
