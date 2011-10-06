<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\teaching_assignment\Module
{
    private $cache_teaching_assignments = array();

    function get_teaching_assignments_data($year = 0, $source = TeachingAssignment::SOURCE_TEACHER)
    {
        if (! isset($this->cache_teaching_assignments[$year][$source]))
        {
            if ($year == 0)
            {
                $teaching_assignments = array();
                foreach ($this->get_teaching_assignments() as $teaching_assignment)
                {
                    if ($teaching_assignment->get_source() == $source)
                    {
                        $teaching_assignments[] = $teaching_assignment;
                    }
                }
            }
            else
            {
                $teaching_assignments = array();
                foreach ($this->get_teaching_assignments() as $teaching_assignment)
                {
                    if ($teaching_assignment->get_year() == $year && $teaching_assignment->get_source() == $source)
                    {
                        $teaching_assignments[] = $teaching_assignment;
                    }
                }
            }
            $this->cache_teaching_assignments[$year][$source] = $teaching_assignments;
        }
        return $this->cache_teaching_assignments[$year][$source];
    }

    function has_teaching_assignments($year, $source)
    {
        return count($this->get_teaching_assignments_data($year, $source)) > 0;
    }

    function get_teaching_assignments_table($year = 0, $source = TeachingAssignment::SOURCE_TEACHER)
    {
        $teaching_assignments = $this->get_teaching_assignments_data($year, $source);
        
        $data = array();
        
        foreach ($teaching_assignments as $key => $teaching_assignment)
        {
            $row = array();
            $row[] = $teaching_assignment->get_year();
            $row[] = $teaching_assignment->get_faculty();
            $row[] = $teaching_assignment->get_training();
            $row[] = $teaching_assignment->get_name();
            $row[] = $teaching_assignment->get_credits();
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
        $table->set_header(1, Translation :: get('Faculty'), false);
        $table->set_header(2, Translation :: get('Training'), false);
        $table->set_header(3, Translation :: get('Name'), false);
        $table->set_header(4, Translation :: get('Credits'), false, 'class="action"');
        
        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\teaching_assignment\Module::render()
     */
    function render()
    {
        $html = array();
        
        $years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_application()->get_user_id());
        
        $tabs = new DynamicTabsRenderer('teaching_assignment_list');
        
        $source_tabs = new DynamicTabsRenderer('teaching_assignment_year_list');
        
        if ($this->has_teaching_assignments(0, TeachingAssignment :: SOURCE_TEACHER))
        {
            $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: SOURCE_TEACHER, Translation :: get('Teacher'), null, $this->get_teaching_assignments_table(0, TeachingAssignment :: SOURCE_TEACHER)->toHTML()));
        }
        if ($this->has_teaching_assignments(0, TeachingAssignment :: SOURCE_MANAGER))
        {
            $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: SOURCE_MANAGER, Translation :: get('Manager'), null, $this->get_teaching_assignments_table(0, TeachingAssignment :: SOURCE_MANAGER)->toHTML()));
        }
        
        $tabs->add_tab(new DynamicContentTab(0, Translation :: get('AllYears'), null, $source_tabs->render()));
        
        foreach ($years as $year)
        {
            $source_tabs = new DynamicTabsRenderer('teaching_assignment_year_' . $year . '_list');
            
            if ($this->has_teaching_assignments($year, TeachingAssignment :: SOURCE_TEACHER))
            {
                $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: SOURCE_TEACHER, Translation :: get('Teacher'), null, $this->get_teaching_assignments_table($year, TeachingAssignment :: SOURCE_TEACHER)->toHTML()));
            }

            if ($this->has_teaching_assignments($year, TeachingAssignment :: SOURCE_MANAGER))
            {
                $source_tabs->add_tab(new DynamicContentTab(TeachingAssignment :: SOURCE_MANAGER, Translation :: get('Manager'), null, $this->get_teaching_assignments_table($year, TeachingAssignment :: SOURCE_MANAGER)->toHTML()));
            }
            $tabs->add_tab(new DynamicContentTab($year, $year, null, $source_tabs->render()));
        }
        
        $html[] = $tabs->render();
        
        return implode("\n", $html);
    }
}
?>