<?php
namespace application\discovery\module\faculty;

use application\discovery\Parameters;

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
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    /**
     * @var multitype:\application\discovery\module\faculty\Faculty
     */
    private $faculties;
    private $cache_faculties = array();

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->faculties = DataManager :: get_instance($module_instance)->retrieve_faculties();
    
    }

    static function get_module_parameters()
    {
        return new Parameters();
    }

    /**
     * @return multitype:\application\discovery\module\faculty\Faculty
     */
    function get_faculties()
    {
        return $this->faculties;
    }

    function get_faculties_data($year = 0)
    {
        if (! isset($this->cache_faculties[$year]))
        {
            if ($year == 0)
            {
                $faculties = array();
                foreach ($this->get_faculties() as $faculty)
                {
                    $faculties[] = $faculty;
                }
            }
            else
            {
                $faculties = array();
                foreach ($this->get_faculties() as $faculty)
                {
                    if ($faculty->get_year() == $year)
                    {
                        $faculties[] = $faculty;
                    }
                }
            }
            $this->cache_faculties[$year] = $faculties;
        }
        return $this->cache_faculties[$year];
    }

    function has_faculties($year)
    {
        return count($this->get_faculties_data($year)) > 0;
    }

    function get_faculties_table($year = 0)
    {
        $faculties = $this->get_faculties_data($year);
        
        $data = array();
        
        foreach ($faculties as $key => $faculty)
        {
            $row = array();
            if (! $year)
            {
                $row[] = $faculty->get_year();
            }
            //            $data_source = $this->get_module_instance()->get_setting('data_source');
            //            $course_result_module_instance = \application\discovery\Module :: exists('application\discovery\module\course_results\implementation\bamaflex', array(
            //                    'data_source' => $data_source));
            //
            //            if ($course_result_module_instance)
            //            {
            //                $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters($faculty->get_programme_id(), 1);
            //                $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
            //                $row[] = '<a href="' . $url . '">' . $faculty->get_name() . '</a>';
            //            }
            //            else
            //            {
            $row[] = $faculty->get_name();
            //            }
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        if (! $year)
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Name'), false);
        }
        else
        {
            $table->set_header(0, Translation :: get('Name'), false);
        }
        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\faculty\Module::render()
     */
    function render()
    {
        $html = array();
        
        $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_application()->get_user_id());
        
        $tabs = new DynamicTabsRenderer('faculty_list');
        
        //        $tabs->add_tab(new DynamicContentTab(0, Translation :: get('AllYears'), null, $this->get_faculties_table(0)->toHTML()));
        

        foreach ($years as $year)
        {
            $tabs->add_tab(new DynamicContentTab($year, $year, null, $this->get_faculties_table($year)->toHTML()));
        }
        
        $html[] = $tabs->render();
        
        return implode("\n", $html);
    }

}
?>