<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex;

use Chamilo\Libraries\Platform\Session\Request;

class Module extends \Chamilo\Application\Discovery\Module\TrainingResults\Module
{
    const PARAM_SOURCE = 'source';

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
    {
        $training_id = Request :: get(self :: PARAM_TRAINING_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $parameter = new Parameters();
        
        if ($training_id)
        {
            $parameter->set_training_id($training_id);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        return $parameter;
    }

    public static function get_training_info_parameters()
    {
        $training_id = Request :: get(self :: PARAM_TRAINING_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters();
        $parameter->set_training_id($training_id);
        
        if ($source)
        {
            $parameter->set_source($source);
        }
        
        return $parameter;
    }
}
