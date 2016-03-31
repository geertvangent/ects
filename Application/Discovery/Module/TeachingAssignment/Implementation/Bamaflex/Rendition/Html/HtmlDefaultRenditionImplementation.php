<?php
namespace Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));

        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT,
            $this->get_module_instance()->get_id(),
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }

        if (is_null($this->get_module_parameters()->get_year()))
        {
            $years = $this->get_years();
            $current_year = $years[0];
        }
        else
        {
            $current_year = $this->get_module_parameters()->get_year();
        }
        $parameters = $this->get_module_parameters();
        $parameters->set_year($current_year);

        $html = array();

        if ($this->has_data())
        {
            $tabs = new DynamicVisualTabsRenderer(
                'teaching_assignment_list',
                $this->get_teaching_assignments_table($parameters)->toHTML());

            foreach ($this->get_years() as $year)
            {
                $parameters = $this->get_module_parameters();
                $parameters->set_year($year);
                $tabs->add_tab(
                    new DynamicVisualTab(
                        $year,
                        $year,
                        null,
                        $this->get_instance_url($this->get_module_instance()->get_id(), $parameters),
                        $current_year == $year));
            }

            $html[] = $tabs->render();

            \Ehb\Application\Discovery\Rendition\View\Html\HtmlDefaultRendition :: add_export_action($this);
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }

        return implode(PHP_EOL, $html);
    }

    public function get_teaching_assignments_table($parameters)
    {
        $teaching_assignments = $this->get_teaching_assignments_data($parameters);
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\course\Implementation\Bamaflex',
            array('data_source' => $data_source));

        $course_result_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\course_results\Implementation\Bamaflex',
            array('data_source' => $data_source));

        $faculty_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\faculty_info\Implementation\Bamaflex',
            array('data_source' => $data_source));

        $training_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\training_info\Implementation\Bamaflex',
            array('data_source' => $data_source));

        foreach ($teaching_assignments as $key => $teaching_assignment)
        {
            $row = array();

            if ($faculty_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Parameters(
                    $teaching_assignment->get_faculty_id(),
                    $teaching_assignment->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $faculty_info_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_faculty() . '</a>';
                }
                else
                {
                    $row[] = $teaching_assignment->get_faculty();
                }
            }
            else
            {
                $row[] = $teaching_assignment->get_faculty();
            }

            if ($training_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters(
                    $teaching_assignment->get_training_id(),
                    $teaching_assignment->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $faculty_info_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_training() . '</a>';
                }
                else
                {
                    $row[] = $teaching_assignment->get_training();
                }
            }
            else
            {
                $row[] = $teaching_assignment->get_training();
            }

            $image = '<img src="' . Theme :: getInstance()->getImagesPath() . 'Type/' .
                 $teaching_assignment->get_manager() . '.png" alt="' .
                 Translation :: get($teaching_assignment->get_manager_type()) . '" title="' .
                 Translation :: get($teaching_assignment->get_manager_type()) . '"/>';
            $row[] = $image;
            LegendTable :: getInstance()->addSymbol(
                $image,
                Translation :: get($teaching_assignment->get_manager_type()),
                Translation :: get('Manager'));

            $image = '<img src="' . Theme :: getInstance()->getImagesPath() . 'Type/' .
                 $teaching_assignment->get_teacher() . '.png" alt="' .
                 Translation :: get($teaching_assignment->get_teacher_type()) . '" title="' .
                 Translation :: get($teaching_assignment->get_teacher_type()) . '"/>';
            $row[] = $image;
            LegendTable :: getInstance()->addSymbol(
                $image,
                Translation :: get($teaching_assignment->get_teacher_type()),
                Translation :: get('Teacher'));

            if ($course_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters(
                    $teaching_assignment->get_programme_id(),
                    $teaching_assignment->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $course_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_name() . '</a>';
                }
                else
                {
                    $row[] = $teaching_assignment->get_name();
                }
            }
            else
            {
                $row[] = $teaching_assignment->get_name();
            }
            $row[] = $teaching_assignment->get_credits();
            $image = '<img src="' . Theme :: getInstance()->getImagesPath() . 'Timeframe/' .
                 $teaching_assignment->get_timeframe_id() . '.png" alt="' .
                 Translation :: get($teaching_assignment->get_timeframe()) . '" title="' .
                 Translation :: get($teaching_assignment->get_timeframe()) . '"/>';
            $row[] = $image;
            LegendTable :: getInstance()->addSymbol(
                $image,
                Translation :: get($teaching_assignment->get_timeframe()),
                Translation :: get('Timeframe'));

            if ($course_result_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Parameters(
                    $teaching_assignment->get_programme_id(),
                    $teaching_assignment->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $course_result_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                    $row[] = Theme :: getInstance()->getCommonImage(
                        'Action/Details',
                        'png',
                        Translation :: get('CourseResults'),
                        $url,
                        ToolbarItem :: DISPLAY_ICON);
                }
                else
                {
                    $row[] = Theme :: getInstance()->getCommonImage(
                        'Action/DetailsNa',
                        'png',
                        Translation :: get('CourseResultsNotAvailable'),
                        null,
                        ToolbarItem :: DISPLAY_ICON);
                }
            }

            $data[] = $row;
        }

        $table = new SortableTable($data);

        $table->setColumnHeader(0, Translation :: get('Faculty'), false);
        $table->setColumnHeader(1, Translation :: get('Training'), false);
        $table->setColumnHeader(2, '<img src="' . Theme :: getInstance()->getImagesPath() . 'manager.png"/>', false);
        $table->setColumnHeader(3, '<img src="' . Theme :: getInstance()->getImagesPath() . 'teacher.png"/>', false);
        $table->setColumnHeader(4, Translation :: get('Name'), false);
        $table->setColumnHeader(5, Translation :: get('Credits'), false, 'class="action"');
        $table->setColumnHeader(6, '<img src="' . Theme :: getInstance()->getImagesPath() . 'timeframe.png"/>', false);
        $table->setColumnHeader(7, '', false);

        return $table;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
