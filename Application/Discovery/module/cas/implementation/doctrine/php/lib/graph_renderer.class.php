<?php
namespace application\discovery\module\cas\implementation\doctrine;

use application\discovery\SortableTable;
use application\discovery\module\cas\DataManager;
use common\libraries\Filesystem;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\Path;
use common\libraries\EqualityCondition;
use common\libraries\AndCondition;
use common\libraries\InequalityCondition;
use pChart;

require_once Path :: get_plugin_path() . '/pChart/pChart/pChart.class';
require_once Path :: get_plugin_path() . '/pChart/pChart/pData.class';
class GraphRenderer
{

    private $user_id;

    private $application;

    private $action;

    private $module;

    private $months;

    private $statistics;

    private $graph_data = array();

    public function __construct($module, $user_id, $application, $action, $statistics)
    {
        $this->user_id = $user_id;
        $this->application = $application;
        $this->action = $action;
        $this->statistics = $statistics;
        $this->module = $module;
        
        $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/graph_data/' .
             md5(serialize(array($user_id, $application, $action)));
        
        if (! file_exists($path))
        {
            $this->graph_data[] = $this->get_data();
            $this->graph_data[] = $this->get_config();
            Filesystem :: write_to_file($path, serialize($this->graph_data));
        }
        else
        {
            $this->graph_data = unserialize(file_get_contents($path));
        }
    }

    public function table()
    {
        $url_format = $this->url_format;
        $table_data = array();
        
        // Set the table data
        foreach ($this->get_months() as $key => $month)
        {
            $row = array();
            $row[] = date('Y-m', $month);
            $row[] = $this->graph_data[0][$key][$this->application->get_id()];
            
            $table_data[] = $row;
        }
        $table = new SortableTable($table_data);
        
        $table->set_header(0, Translation :: get('Month'), false);
        $table->set_header(1, Translation :: get('Count'), false);
        
        return $table->as_html();
    }

    public function chart()
    {
        $image_id = md5(serialize($this->graph_data));
        $image_path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/chart/';
        $image_file = $image_id . '.png';
        
        $alt_title = Translation :: get('CasStatistics') . ' - ' . $this->graph_data[1]['Title'];
        
        if (! file_exists($image_path . $image_file))
        {
            if (! is_dir($image_path))
            {
                Filesystem :: create_dir($image_path);
            }
            $font = Path :: get_plugin_path() . 'pChart/Fonts/tahoma.ttf';
            
            $graph = new pChart(840, 490);
            $graph->reportWarnings();
            $graph->loadColorPalette(Path :: get(SYS_LAYOUT_PATH) . Theme :: get_theme() . '/plugin/pchart/tones.txt');
            $graph->setFontProperties($font, 8);
            $graph->setGraphArea(70, 50, 800, 400);
            $graph->drawFilledRoundedRectangle(7, 7, 833, 483, 5, 240, 240, 240);
            $graph->drawRoundedRectangle(5, 5, 835, 485, 5, 230, 230, 230);
            $graph->drawGraphArea(255, 255, 255, TRUE);
            $graph->drawScale(
                $this->graph_data[0], 
                $this->graph_data[1], 
                SCALE_START0, 
                150, 
                150, 
                150, 
                TRUE, 
                60, 
                0, 
                TRUE);
            $graph->drawGrid(4, TRUE, 230, 230, 230, 50);
            
            // Draw the 0 line
            $graph->setFontProperties($font, 6);
            $graph->drawTreshold(0, 143, 55, 72, TRUE, TRUE);
            
            // Draw the bar graph
            $graph->drawLineGraph($this->graph_data[0], $this->graph_data[1]);
            
            // Finish the graph
            $graph->setFontProperties($font, 10);
            $graph->drawTitle(50, 32, $this->graph_data[1]['Title'], 50, 50, 50, 840 - 50);
            
            $graph->Render($image_path . $image_file);
        }
        
        $web_path = Path :: get(WEB_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/chart/' . $image_file;
        
        return '<img src="' . $web_path . '" border="0" alt="' . $alt_title . '" title="' . $alt_title . '" />';
    }

    public function get_config()
    {
        $config = array();
        
        $config['Title'] = Translation :: get($this->action->get_name()) . ' ' . $this->application->get_title();
        $config['Position'] = 'Name';
        $config['Axis'] = array('X' => Translation :: get('Months'), 'Y' => Translation :: get('Count'));
        $config['Values'] = array();
        $config['Description'] = array();
        
        $config['Values'][] = $this->application->get_id();
        $config['Description'][$this->application->get_id()] = Translation :: get($this->action->get_name()) . ' ' .
             $this->application->get_title();
        
        return $config;
    }

    public function get_months()
    {
        if (! isset($this->months))
        {
            $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/months/' .
                 md5(serialize(array($this->user_id, $this->action, $this->application)));
            
            if (! file_exists($path))
            {
                $first_date = DataManager :: get_instance($this->module->get_module_instance())->retrieve_first_date(
                    $this->user_id, 
                    $this->action, 
                    $this->application);
                $first_date = strtotime($first_date);
                
                $this->months = array();
                $this->months[] = $first_date;
                
                $today = time();
                $next_month = strtotime("+1 month", $first_date);
                
                while ($next_month < $today)
                {
                    $this->months[] = $next_month;
                    $next_month = strtotime("+1 month", $next_month);
                }
                Filesystem :: write_to_file($path, serialize($this->months));
            }
            else
            {
                $this->months = unserialize(file_get_contents($path));
            }
        }
        return $this->months;
    }

    public function get_data()
    {
        $data = array();
        
        foreach ($this->get_months() as $key => $month)
        {
            $formatted_month = date('Y-m', $month);
            $data[$key] = array('Name' => $formatted_month);
            if (isset($this->statistics[$formatted_month]))
            {
                $data[$key][$this->application->get_id()] = $this->statistics[$formatted_month][1];
            }
            else
            {
                $data[$key][$this->application->get_id()] = 0;
            }
        }
        
        // $user = \user\DataManager :: retrieve(\user\User :: class_name(), (int) $this->user_id);
        
        // $official_code = $user->get_official_code();
        
        // foreach ($this->get_months() as $key => $month)
        // {
        // $conditions = array();
        // $conditions[] = new EqualityCondition(Cas :: PROPERTY_PERSON_ID, $official_code);
        // $conditions[] = new EqualityCondition(Cas :: PROPERTY_ACTION_ID, $this->action->get_id());
        // $conditions[] = new EqualityCondition(Cas :: PROPERTY_APPLICATION_ID, $this->application->get_id());
        // $first_day_of_month = mktime(0, 0, 0, date('n', $month), 1, date('Y', $month));
        // $first_day_of_next_month = strtotime("+1 month", $first_day_of_month);
        // $conditions[] = new InequalityCondition(Cas :: PROPERTY_DATE, InEqualityCondition :: GREATER_THAN_OR_EQUAL,
        // date('Y-m-d G:i:s', $first_day_of_month));
        // $conditions[] = new InequalityCondition(Cas :: PROPERTY_DATE, InEqualityCondition :: LESS_THAN,
        // date('Y-m-d
        // G:i:s', $first_day_of_next_month));
        
        // $condition = new AndCondition($conditions);
        
        // $count = DataManager :: get_instance($this->module->get_module_instance())->count_cas_graph_statistics(
        // $condition);
        // $data[$key][$this->application->get_id()] = $count;
        // }
        
        return $data;
    }
}
