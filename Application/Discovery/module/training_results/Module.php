<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Request;
use Chamilo\Application\Discovery\Instance\Instance;
use Chamilo\Application\Discovery\Module\TrainingResults\DataManager;

class Module extends \Chamilo\Application\Discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\training_results\Course
     */
    private $training_results;

    public function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    public function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_TRAINING_ID));
    }

    /**
     *
     * @return multitype:\application\discovery\module\training_results\Course
     */
    public function get_training_results()
    {
        if (! isset($this->training_results))
        {
            $this->training_results = $this->get_data_manager()->retrieve_training_results(
                $this->get_module_parameters());
        }
        return $this->training_results;
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
}
