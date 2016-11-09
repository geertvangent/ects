<?php
namespace Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Chamilo\Core\Reporting\Viewer\Chart\pChamiloImage;
use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\Cas\DataManager;
use Ehb\Application\Discovery\SortableTable;

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

        $path = Path :: getInstance()->getStoragePath() .
             ClassnameUtilities :: getInstance()->namespaceToPath(__NAMESPACE__) . '/graph_data/' .
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

        $table->setColumnHeader(0, Translation :: get('Month'), false);
        $table->setColumnHeader(1, Translation :: get('Count'), false);

        return $table->as_html();
    }

    public function chart()
    {
        $image_id = md5(serialize($this->graph_data));
        $image_path = Path :: getInstance()->getStoragePath() .
             ClassnameUtilities :: getInstance()->namespaceToPath(__NAMESPACE__) . '/chart/';
        $image_file = $image_id . '.png';

        $alt_title = Translation :: get('CasStatistics') . ' - ' . $this->graph_data[1]['Title'];

        if (! file_exists($image_path . $image_file))
        {
            if (! is_dir($image_path))
            {
                Filesystem :: create_dir($image_path);
            }

            $width = 840;
            $height = 490;
            $graph_area_left = 70;
            $graph_area_bottom = 400;

            $chart_canvas = new pChamiloImage($width, $height, $this->graph_data);

            /* Draw a solid background */
            $format = array('R' => 240, 'G' => 240, 'B' => 240);
            $chart_canvas->drawFilledRectangle(0, 0, $width - 1, $height - 1, $format);

            /* Add a border to the picture */
            $format = array('R' => 255, 'G' => 255, 'B' => 255);
            $chart_canvas->drawRectangle(1, 1, $width - 2, $height - 2, $format);

            /* Set the default font properties */
            $chart_canvas->setFontProperties(
                array(
                    'FontName' => Path :: getInstance()->getVendorPath() .
                         'szymach/c-pchart/src/Resources/fontspchart/fonts/Verdana.ttf',
                        'FontSize' => 8,
                        'R' => 0,
                        'G' => 0,
                        'B' => 0));

            /* Draw the scale */
            $chart_canvas->setGraphArea($graph_area_left, 20, $width - 21, $graph_area_bottom - 1);
            $chart_canvas->drawFilledRectangle(
                $graph_area_left,
                20,
                $width - 21,
                $graph_area_bottom - 1,
                array('R' => 0, 'G' => 0, 'B' => 0, 'Surrounding' => - 200, 'Alpha' => 3));
            $chart_canvas->drawScale(
                array(
                    'DrawSubTicks' => TRUE,
                    'LabelRotation' => 315,
                    'GridR' => 0,
                    'GridG' => 0,
                    'GridB' => 0,
                    'GridAlpha' => 7));
            $chart_canvas->setShadow(TRUE, array('X' => 1, 'Y' => 1, 'R' => 0, 'G' => 0, 'B' => 0, 'Alpha' => 10));
            $chart_canvas->drawLineChart(
                array(
                    'DisplayValues' => true,
                    'DisplayColor' => DISPLAY_AUTO,
                    'Surrounding' => 30,
                    'DisplayOffset' => 3));

            /* Render the picture */
            $chart_canvas->render($image_path . $image_file);
        }

        $web_path = Path :: getInstance()->getStoragePath(true) .
             ClassnameUtilities :: getInstance()->namespaceToPath(__NAMESPACE__) . '/chart/' . $image_file;

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
            $path = Path :: getInstance()->getStoragePath() .
                 ClassnameUtilities :: getInstance()->namespaceToPath(__NAMESPACE__) . '/months/' .
                 md5(serialize(array($this->user_id, $this->action, $this->application)));

            if (! file_exists($path))
            {
                $first_date = DataManager :: getInstance($this->module->get_module_instance())->retrieve_first_date(
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

        // PROPERTY_ACTION_ID), new StaticConditionVariable($this->action->get_id()));
        // $conditions[] = new EqualityCondition(new PropertyConditionVariable(Cas :: class_name(), Cas ::
        // PROPERTY_APPLICATION_ID), new StaticConditionVariable($this->application->get_id()));
        return $data;
    }
}
