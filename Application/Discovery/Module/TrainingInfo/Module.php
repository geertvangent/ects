<?php
namespace Chamilo\Application\Discovery\Module\TrainingInfo;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\File\Path;
use Chamilo\Application\Discovery\Instance\DataClass\Instance;

abstract class Module extends \Chamilo\Application\Discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\training_info\Faculty
     */
    private $training;

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
    {
        $training = Request :: get(self :: PARAM_TRAINING_ID);
        
        $parameter = new Parameters();
        if ($training)
        {
            $parameter->set_training_id($training);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\training_info\Faculty
     */
    public function get_training()
    {
        if (! isset($this->training))
        {
            $this->training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                $this->get_module_parameters());
        }
        return $this->training;
    }

    public function get_type()
    {
        return Instance :: TYPE_DETAILS;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            Path :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'implementation/', 
            Filesystem :: LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
