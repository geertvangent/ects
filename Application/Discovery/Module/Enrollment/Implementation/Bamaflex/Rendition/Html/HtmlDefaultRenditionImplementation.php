<?php
namespace Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Enrollment\DataManager;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function get_enrollments_table($contract_type = Enrollment :: CONTRACT_TYPE_ALL)
    {
        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $enrollments = $this->get_enrollments();
        }
        else
        {
            $enrollments = array();
            foreach ($this->get_enrollments() as $enrollment)
            {
                if ($enrollment->get_contract_type() == $contract_type)
                {
                    $enrollments[] = $enrollment;
                }
            }
        }

        $data = array();

        $data_source = $this->get_module_instance()->get_setting('data_source');
        $faculty_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex',
            array('data_source' => $data_source));

        $training_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex',
            array('data_source' => $data_source));

        foreach ($enrollments as $key => $enrollment)
        {
            $row = array();
            $row[] = $enrollment->get_year();

            if ($faculty_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Parameters(
                    $enrollment->get_faculty_id(),
                    $enrollment->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $faculty_info_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $enrollment->get_faculty() . '</a>';
                }
                else
                {
                    $row[] = $enrollment->get_faculty();
                }
            }
            else
            {
                $row[] = $enrollment->get_faculty();
            }

            if ($training_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters(
                    $enrollment->get_training_id(),
                    $enrollment->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $training_info_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $enrollment->get_training() . '</a>';
                }
                else
                {
                    $row[] = $enrollment->get_training();
                }
            }
            else
            {
                $row[] = $enrollment->get_training();
            }

            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();

            if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
            {
                $row[] = Translation :: get($enrollment->get_contract_type_string());
            }

            if ($enrollment->is_special_result())
            {
                $image = '<img src="' . Theme :: getInstance()->getImagesPath(
                    'Ehb/Application/Discovery/Module/Enrollment/Implementation/Bamaflex') . 'ResultType/' .
                     $enrollment->get_result() . '.png" alt="' . Translation :: get($enrollment->get_result_string()) .
                     '" title="' . Translation :: get($enrollment->get_result_string()) . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get($enrollment->get_result_string()),
                    Translation :: get('ResultType'));
            }
            else
            {
                $row[] = ' ';
            }

            if ($enrollment->get_generation_student() == 1)
            {
                $image = '<img src="' . Theme :: getInstance()->getImagesPath(
                    'Ehb/Application/Discovery/Module/Enrollment/Implementation/Bamaflex') . 'GenerationStudent/' .
                     $enrollment->get_generation_student() . '.png" alt="' . Translation :: get('GenerationStudent') .
                     '" title="' . Translation :: get('GenerationStudent') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol(
                    $image,
                    Translation :: get('GenerationStudent'),
                    Translation :: get('Enrollment'));
            }
            else
            {
                $row[] = ' ';
            }

            // $class = 'enrollment" style="" id="enrollment_' . $key;
            // $details_action = new ToolbarItem(Translation :: get('ShowCourses'), Theme ::
            // getInstance()->getCommonImagesPath() .
            // 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            // $row[] = $details_action->as_html();
            $data[] = $row;
        }

        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false);
        $table->set_header(1, Translation :: get('Faculty'), false);
        $table->set_header(2, Translation :: get('Training'), false);
        $table->set_header(3, Translation :: get('Option'), false);
        $table->set_header(4, Translation :: get('Trajectory'), false);
        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $table->set_header(5, Translation :: get('Contract'), false);
            $table->set_header(6, '', false);
            $table->set_header(7, '', false);
        }
        else
        {
            $table->set_header(5, '', false);
            $table->set_header(6, '', false);
        }

        return $table;
    }

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));

        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT,
            $this->get_module_instance()->get_id(),
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }

        $html = array();
        if (count($this->get_enrollments()) > 0)
        {
            $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types(
                $this->get_module_parameters());

            $tabs = new DynamicTabsRenderer('enrollment_list');
            $tabs->add_tab(
                new DynamicContentTab(
                    Enrollment :: CONTRACT_TYPE_ALL,
                    Translation :: get('AllContracts'),
                    Theme :: getInstance()->getImagesPath(
                        'Ehb/Application/Discovery/Module/Enrollment/Implementation/Bamaflex') . 'ContractType/0.png',
                    $this->get_enrollments_table(Enrollment :: CONTRACT_TYPE_ALL)->toHTML()));

            foreach ($contract_types as $contract_type)
            {
                $tabs->add_tab(
                    new DynamicContentTab(
                        $contract_type,
                        Translation :: get(Enrollment :: contract_type_string($contract_type)),
                        Theme :: getInstance()->getImagesPath(
                            'Ehb/Application/Discovery/Module/Enrollment/Implementation/Bamaflex') . 'ContractType/' .
                             $contract_type . '.png',
                            $this->get_enrollments_table($contract_type)->toHTML()));
            }

            $html[] = $tabs->render();
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }

        \Ehb\Application\Discovery\Rendition\View\Html\HtmlDefaultRendition :: add_export_action($this);

        return implode(PHP_EOL, $html);
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
