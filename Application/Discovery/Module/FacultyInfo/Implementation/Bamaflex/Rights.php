<?php
namespace Chamilo\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex;

use Chamilo\Application\Discovery\Module\FacultyInfo\DataManager;
use Chamilo\Application\Discovery\Rights\FacultyBasedRights;

class Rights extends FacultyBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $module_instance = \Chamilo\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(),
            (int) $module_instance_id);
        $faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($parameters);

        return $faculty->get_id();
    }
}
