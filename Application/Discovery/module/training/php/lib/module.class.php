<?php
namespace application\discovery\module\training;

use common\libraries\Filesystem;

use application\discovery\Parameters;

use common\libraries\Request;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Path;
use common\libraries\WebApplication;
use common\libraries\ResourceManager;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\SortableTable;
use application\discovery\ModuleInstance;

class Module extends \application\discovery\Module
{
    /**
     * @var multitype:\application\discovery\module\training\Faculty
     */
    private $trainings;
    private $cache_trainings = array();

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_training_parameters()
    {
        return self :: get_module_parameters();
    }

    static function get_module_parameters()
    {
        return new Parameters();
    }

    /**
     * @return multitype:\application\discovery\module\training\Faculty
     */
    function get_trainings()
    {
        if (! isset($this->trainings))
        {
            $this->trainings = DataManager :: get_instance($this->get_module_instance())->retrieve_trainings($this->get_training_parameters());
        }
        return $this->trainings;
    }

    function get_trainings_data($year = 0)
    {
        if (! isset($this->cache_trainings[$year]))
        {
            if ($year == 0)
            {
                $trainings = array();
                foreach ($this->get_trainings() as $training)
                {
                    $trainings[] = $training;
                }
            }
            else
            {
                $trainings = array();
                foreach ($this->get_trainings() as $training)
                {
                    if ($training->get_year() == $year)
                    {
                        $trainings[] = $training;
                    }
                }
            }
            $this->cache_trainings[$year] = $trainings;
        }
        return $this->cache_trainings[$year];
    }

    function has_trainings($year)
    {
        return count($this->get_trainings_data($year)) > 0;
    }

    function get_trainings_table($year = 0)
    {
        $trainings = $this->get_trainings_data($year);

        $data = array();

        foreach ($trainings as $key => $training)
        {
            $row = array();
            if (! $year && ! $this->has_parameters())
            {
                $row[] = $training->get_year();
            }
            $row[] = $training->get_name();
            $row[] = $training->get_start_date();
            $row[] = $training->get_end_date();
            $data[] = $row;
        }

        $table = new SortableTable($data);

        if (! $year && ! $this->has_parameters())
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('StartDate'), false);
            $table->set_header(3, Translation :: get('EndDate'), false);
        }
        else
        {
            $table->set_header(0, Translation :: get('Name'), false);
            $table->set_header(1, Translation :: get('StartDate'), false);
            $table->set_header(2, Translation :: get('EndDate'), false);
        }
        return $table;
    }

    function has_parameters()
    {
        return false;
    }

    function get_context()
    {
        return '';
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\training\Module::render()
     */
    function render()
    {
        $html = array();

        if ($this->has_parameters())
        {
            $html[] = $this->get_context();
            $html[] = $this->get_trainings_table()->toHTML();
        }
        else
        {
            $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_application()->get_user_id());

            $tabs = new DynamicTabsRenderer('training_list');
            foreach ($years as $year)
            {
                $tabs->add_tab(new DynamicContentTab($year, $year, null, $this->get_trainings_table($year)->toHTML()));
            }
            $html[] = $tabs->render();

        }
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_INFORMATION;
    }

    static function get_available_implementations()
    {
        $types = array();

        $modules = Filesystem :: get_directory_content(Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
?>