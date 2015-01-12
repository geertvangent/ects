<?php
namespace Chamilo\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex;

use Chamilo\Libraries\Platform\Request;

class Module extends \Chamilo\Application\Discovery\Module\FacultyInfo\Module
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
