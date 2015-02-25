<?php
namespace Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex;

use Chamilo\Libraries\Platform\Session\Request;

class Module extends \Ehb\Application\Discovery\Module\CourseResults\Module
{
    const PARAM_SOURCE = 'source';

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
    {
        $programme = Request :: get(self :: PARAM_PROGRAMME_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $parameter = new Parameters();
        
        if ($programme)
        {
            $parameter->set_programme_id($programme);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        return $parameter;
    }

    public static function get_course_parameters()
    {
        $programme_id = Request :: get(self :: PARAM_PROGRAMME_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters();
        $parameter->set_programme_id($programme_id);
        
        if ($source)
        {
            $parameter->set_source($source);
        }
        
        return $parameter;
    }
}
