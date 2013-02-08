<?php
namespace application\discovery\module\faculty;

use common\libraries\Request;
use common\libraries\Filesystem;
use common\libraries\Path;
use common\libraries\Application;
use application\discovery\ModuleInstance;

class Module extends \application\discovery\Module
{
    const PARAM_YEAR = 'year';

    /**
     *
     * @var multitype:\application\discovery\module\faculty\Faculty
     */
    private $faculties;

    private $cache_faculties = array();

    private $years;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    static function module_parameters()
    {
        $year = Request :: get(self :: PARAM_YEAR);

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
    function get_faculties($year)
    {
        if (! isset($this->faculties[$year]))
        {
            $this->faculties[$year] = DataManager :: get_instance($this->get_module_instance())->retrieve_faculties(
                    $year);
        }
        return $this->faculties[$year];
    }

    function get_faculties_data($year)
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

    function get_years()
    {
        if (! isset($this->years))
        {
            $this->years = DataManager :: get_instance($this->get_module_instance())->retrieve_years();
        }
        return $this->years;
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_INFORMATION;
    }

    static function get_available_implementations()
    {
        $types = array();

        $modules = Filesystem :: get_directory_content(
                Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
