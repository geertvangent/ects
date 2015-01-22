<?php
namespace Chamilo\Application\Discovery\Module\CourseResults\Implementation\Bamaflex;

use Chamilo\Application\Discovery\Module\CourseResults\DataManager;
use Chamilo\Application\Discovery\Rights\TrainingBasedContext;
use Chamilo\Application\Discovery\Rights\TrainingBasedRights;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters();
        $parameter->set_programme_id($parameters->get_programme_id());
        $parameter->set_source($parameters->get_source());

        $module_instance = \Chamilo\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(),
            (int) $module_instance_id);

        $course = DataManager :: get_instance($module_instance)->retrieve_course($parameter);

        return new TrainingBasedContext($course->get_faculty_id(), $course->get_training_id());
    }
}
