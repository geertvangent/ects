<?php
namespace Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Chamilo\Libraries\Platform\Request;

class Module extends \Chamilo\Application\Discovery\Module\Course\Module
{
    const PARAM_SOURCE = 'source';
    const TAB_GENERAL = 1;
    const TAB_MATERIALS = 2;
    const TAB_ACTIVITIES = 3;
    const TAB_COMPETENCES = 4;
    const TAB_CONTENT = 5;
    const TAB_EVALUATIONS = 6;

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
}
