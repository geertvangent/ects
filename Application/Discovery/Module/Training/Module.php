<?php
namespace Ehb\Application\Discovery\Module\Training;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;

class Module extends \Ehb\Application\Discovery\Module
{
    const PARAM_YEAR = 'year';

    /**
     *
     * @var multitype:\application\discovery\module\training\Faculty
     */
    private $trainings;

    private $cache_trainings = array();

    private $years;

    public static function module_parameters()
    {
        $year = Request::get(self::PARAM_YEAR);
        
        $parameter = new Parameters();
        if ($year)
        {
            $parameter->set_year($year);
        }
        
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\training\Faculty
     */
    public function get_trainings($year)
    {
        if (! isset($this->trainings[$year]))
        {
            $this->trainings[$year] = DataManager::getInstance($this->get_module_instance())->retrieve_trainings($year);
        }
        return $this->trainings[$year];
    }

    public function get_trainings_data($year)
    {
        if (! isset($this->cache_trainings[$year]))
        {
            foreach ($this->get_trainings($year) as $training)
            {
                $this->cache_trainings[$year][] = $training;
            }
        }
        return $this->cache_trainings[$year];
    }

    public function get_years()
    {
        if (! isset($this->years))
        {
            $this->years = DataManager::getInstance($this->get_module_instance())->retrieve_years();
        }
        return $this->years;
    }

    public function get_type()
    {
        return Instance::TYPE_INFORMATION;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem::get_directory_content(
            Path::getInstance()->namespaceToFullPath(__NAMESPACE__) . 'implementation/', 
            Filesystem::LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\Implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
