<?php
namespace Ehb\Application\Discovery\Module\Faculty;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;

class Module extends \Ehb\Application\Discovery\Module
{
    const PARAM_YEAR = 'year';

    /**
     *
     * @var multitype:\application\discovery\module\faculty\Faculty
     */
    private $faculties;

    private $cache_faculties = array();

    private $years;

    public function __construct(Application $application, Instance $module_instance)
    {
        parent::__construct($application, $module_instance);
    }

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
     * @return multitype:\application\discovery\module\faculty\Faculty
     */
    public function get_faculties($year)
    {
        if (! isset($this->faculties[$year]))
        {
            $this->faculties[$year] = DataManager::getInstance($this->get_module_instance())->retrieve_faculties($year);
        }
        return $this->faculties[$year];
    }

    public function get_faculties_data($year)
    {
        if (! isset($this->cache_faculties[$year]))
        {
            foreach ($this->get_faculties($year) as $faculty)
            {
                $this->cache_faculties[$year][] = $faculty;
            }
        }
        return $this->cache_faculties[$year];
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
