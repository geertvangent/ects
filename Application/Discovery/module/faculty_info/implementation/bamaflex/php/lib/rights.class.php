<?php
namespace application\discovery\module\faculty_info\implementation\bamaflex;

use application\discovery\FacultyBasedRights;
use application\discovery\module\faculty_info\DataManager;

class Rights extends FacultyBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
            \application\discovery\instance\Instance :: class_name(),
            (int) $module_instance_id);
        $faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($parameters);

        return $faculty->get_id();
    }
}
