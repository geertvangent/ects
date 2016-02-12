<?php
namespace Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Parameters;
use Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT,
            $this->get_module_instance()->get_id(),
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }

        $html = array();

        $html[] = $this->get_context();
        $html[] = $this->get_trainings_table()->toHTML();

        return implode(PHP_EOL, $html);
    }

    public function get_context()
    {
        $html = array();

        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $this->get_faculty()->get_year()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $this->get_faculty()->get_name()));

        $html[] = $this->get_faculty_properties_table()->toHtml();
        $html[] = '<br/>';

        return implode(PHP_EOL, $html);
    }

    public function get_faculty_properties_table()
    {
        $properties = array();
        $properties[Translation :: get('Year')] = $this->get_faculty()->get_year();
        $properties[Translation :: get('Deans')] = $this->get_faculty()->get_deans_string();

        $history = array();
        $faculties = $this->get_faculty()->get_all($this->get_module_instance());

        $i = 1;
        foreach ($faculties as $year => $year_faculties)
        {
            if (count($year_faculties) > 1)
            {
                $multi_history = array();

                foreach ($year_faculties as $faculty)
                {
                    $parameters = new Parameters($faculty->get_id(), $faculty->get_source());

                    $is_allowed = Rights :: is_allowed(
                        Rights :: VIEW_RIGHT,
                        $this->get_module_instance()->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $multi_history[] = '<a href="' . $link . '">' . $faculty->get_name() . '</a>';
                    }
                    else
                    {
                        $multi_history[] = $faculty->get_name();
                    }
                }

                if ($i == 1)
                {
                    $previous_history = array($year, implode('  |  ', $multi_history));
                }
                else
                {
                    $next_history = array($year, implode('  |  ', $multi_history));
                }
            }
            else
            {
                $faculty = $year_faculties[0];

                $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
                $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);

                if ($faculty->has_previous_references() && ! $faculty->has_previous_references(true))
                {
                    if ($i == 1)
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT,
                            $this->get_module_instance()->get_id(),
                            $parameters);

                        if ($is_allowed)
                        {
                            $previous_history = array(
                                $year,
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $previous_history = array($year, $faculty->get_name());
                        }
                    }
                    elseif ($i == count($faculties))
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT,
                            $this->get_module_instance()->get_id(),
                            $parameters);

                        if ($is_allowed)
                        {
                            $next_history = array(
                                $year,
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $next_history = array($year, $faculty->get_name());
                        }
                    }
                    else
                    {
                        $parameters = new Parameters($faculty->get_id(), $faculty->get_source());

                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT,
                            $this->get_module_instance()->get_id(),
                            $parameters);

                        if ($is_allowed)
                        {
                            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                            $history[] = '<a href="' . $link . '" title="' . $faculty->get_name() . '">' .
                                 $faculty->get_year() . '</a>';
                        }
                        else
                        {
                            $history[] = $faculty->get_year();
                        }
                    }
                }
                elseif ($faculty->has_next_references() && ! $faculty->has_next_references(true))
                {
                    if ($i == 1)
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT,
                            $this->get_module_instance()->get_id(),
                            $parameters);

                        if ($is_allowed)
                        {
                            $previous_history = array(
                                $year,
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $previous_history = array($year, $faculty->get_name());
                        }
                    }
                    elseif ($i == count($faculties))
                    {
                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT,
                            $this->get_module_instance()->get_id(),
                            $parameters);

                        if ($is_allowed)
                        {
                            $next_history = array(
                                $year,
                                '<a href="' . $link . '" title="' . $faculty->get_name() . '">' . $faculty->get_name() .
                                     '</a>');
                        }
                        else
                        {
                            $next_history = array($year, $faculty->get_name());
                        }
                    }
                    else
                    {
                        $parameters = new Parameters($faculty->get_id(), $faculty->get_source());

                        $is_allowed = Rights :: is_allowed(
                            Rights :: VIEW_RIGHT,
                            $this->get_module_instance()->get_id(),
                            $parameters);

                        if ($is_allowed)
                        {
                            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                            $history[] = '<a href="' . $link . '" title="' . $faculty->get_name() . '">' .
                                 $faculty->get_year() . '</a>';
                        }
                        else
                        {
                            $history[] = $faculty->get_year();
                        }
                    }
                }
                else
                {
                    $parameters = new Parameters($faculty->get_id(), $faculty->get_source());

                    $is_allowed = Rights :: is_allowed(
                        Rights :: VIEW_RIGHT,
                        $this->get_module_instance()->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $faculty->get_name() . '">' .
                             $faculty->get_year() . '</a>';
                    }
                    else
                    {
                        $history[] = $faculty->get_year();
                    }
                }
            }
            $i ++;
        }

        $properties[Translation :: get('History')] = implode('  |  ', $history);

        if ($previous_history)
        {
            $properties[Translation :: get(
                'HistoryWas',
                array('YEAR' => $previous_history[0]),
                'Ehb\Application\Discovery')] = $previous_history[1];
        }

        if ($next_history)
        {
            $properties[Translation :: get(
                'HistoryBecomes',
                array('YEAR' => $next_history[0]),
                'Ehb\Application\Discovery')] = $next_history[1];
        }

        $data_source = $this->get_module_instance()->get_setting('data_source');
        $photo_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex',
            array('data_source' => $data_source));

        if ($photo_module_instance)
        {
            $is_allowed = \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: is_allowed(
                \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                $photo_module_instance->get_id(),
                $parameters);

            $buttons = array();

            if ($is_allowed)
            {
                // students
                $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
                $parameters->set_faculty_id($this->get_faculty()->get_id());
                $parameters->set_type(\Ehb\Application\Discovery\Module\Photo\Module :: TYPE_STUDENT);

                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                $image = Theme :: getInstance()->getImage(
                    'Type/2',
                    'png',
                    Translation :: get('Students', null, 'Ehb\Application\Discovery\Module\Photo'),
                    $url,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;
                LegendTable :: getInstance()->addSymbol(
                    $image,
                    Translation :: get('Students', null, 'Ehb\Application\Discovery\Module\Photo
                    '),
                    Translation :: get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));

                // teachers
                $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
                $parameters->set_faculty_id($this->get_faculty()->get_id());
                $parameters->set_type(\Ehb\Application\Discovery\Module\Photo\Module :: TYPE_TEACHER);

                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                $image = Theme :: getInstance()->getImage(
                    'Type/1',
                    'png',
                    Translation :: get('Teachers', null, 'Ehb\Application\Discovery\Module\Photo
                    '),
                    $url,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;

                LegendTable :: getInstance()->addSymbol(
                    $image,
                    Translation :: get('Teachers', null, 'Ehb\Application\Discovery\Module\Photo
                    '),
                    Translation :: get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));

                // Employees
                $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
                $parameters->set_faculty_id($this->get_faculty()->get_id());
                $parameters->set_type(\Ehb\Application\Discovery\Module\Photo\Module :: TYPE_EMPLOYEE);

                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                $image = Theme :: getInstance()->getImage(
                    'Type/3',
                    'png',
                    Translation :: get('Employees', null, 'Ehb\Application\Discovery\Module\Photo
                    '),
                    $url,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;

                LegendTable :: getInstance()->addSymbol(
                    $image,
                    Translation :: get('Employees', null, 'Ehb\Application\Discovery\Module\Photo
                    '),
                    Translation :: get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));
            }
            else
            {
                // students
                $image = Theme :: getInstance()->getImage(
                    'Type/2_na',
                    'png',
                    Translation :: get('StudentsNotAvailable', null, 'Ehb\Application\Discovery\Module\Photo'),
                    null,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;
                LegendTable :: getInstance()->addSymbol(
                    $image,
                    Translation :: get(
                        'StudentsNotAvailable',
                        null,
                        'Ehb\Application\Discovery\Module\Photo
                    '),
                    Translation :: get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));

                // teachers
                $image = Theme :: getInstance()->getImage(
                    'Type/1_na',
                    'png',
                    Translation :: get(
                        'TeachersNotAvailable',
                        null,
                        'Ehb\Application\Discovery\Module\Photo
                    '),
                    null,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;

                LegendTable :: getInstance()->addSymbol(
                    $image,
                    Translation :: get(
                        'TeachersNotAvailable',
                        null,
                        'Ehb\Application\Discovery\Module\Photo
                    '),
                    Translation :: get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));

                // Employees
                $image = Theme :: getInstance()->getImage(
                    'Type/3_na',
                    'png',
                    Translation :: get(
                        'EmployeesNotAvailable',
                        null,
                        'Ehb\Application\Discovery\Module\Photo
                    '),
                    null,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    'Ehb\Application\Discovery\Module\Photo');
                $buttons[] = $image;

                LegendTable :: getInstance()->addSymbol(
                    $image,
                    Translation :: get(
                        'EmployeesNotAvailable',
                        null,
                        'Ehb\Application\Discovery\Module\Photo
                    '),
                    Translation :: get('TypeName', null, 'Ehb\Application\Discovery\Module\Photo'));
            }

            $properties[Translation :: get('Photos')] = implode(PHP_EOL, $buttons);
        }
        return new PropertiesTable($properties);
    }

    public function get_trainings_table()
    {
        $trainings = $this->get_trainings_data($this->get_module_parameters());

        $data = array();

        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex',
            array('data_source' => $data_source));

        $group_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex',
            array('data_source' => $data_source));
        $photo_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex',
            array('data_source' => $data_source));

        foreach ($trainings as $key => $training)
        {
            $row = array();

            if ($training_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters(
                    $training->get_id(),
                    $training->get_source());

                $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                    $training_info_module_instance->get_id(),
                    $parameters);

                if ($is_allowed)
                {
                    $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $training->get_name() . '</a>';
                }
                else
                {
                    $row[] = $training->get_name();
                }
            }
            else
            {
                $row[] = $training->get_name();
            }

            $row[] = $training->get_domain();
            $row[] = $training->get_credits();

            $bama_type_image = '<img src="' . Theme :: getInstance()->getImagesPath(
                'Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex') . 'BamaType/' .
                 $training->get_bama_type() . '.png" alt="' .
                 Translation :: get(
                    $training->get_bama_type_string(),
                    null,
                    'Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex') . '" title="' . Translation :: get(
                    $training->get_bama_type_string(),
                    null,
                    'Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex') . '" />';
            $row[] = $bama_type_image;
            LegendTable :: getInstance()->addSymbol(
                $bama_type_image,
                Translation :: get(
                    $training->get_bama_type_string(),
                    null,
                    'Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex'),
                Translation :: get(
                    'BamaType',
                    null,
                    'Ehb\Application\Discovery\Module\Training\Implementation\Bamaflex'));

            if ($group_module_instance || $photo_module_instance)
            {
                $buttons = array();

                if ($group_module_instance)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Parameters(
                        $training->get_id(),
                        $training->get_source());

                    $is_allowed = \Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Rights :: is_allowed(
                        \Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                        $group_module_instance->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($group_module_instance->get_id(), $parameters);
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('Groups'),
                            Theme :: getInstance()->getImagesPath(
                                'Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex') . 'Logo/16.png',
                            $url,
                            ToolbarItem :: DISPLAY_ICON);
                    }
                    else
                    {
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('GroupsNotAvailable'),
                            Theme :: getInstance()->getImagesPath(
                                'Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex') . 'Logo/16_na.png',
                            null,
                            ToolbarItem :: DISPLAY_ICON);
                    }

                    $buttons[] = $toolbar_item->as_html();
                }

                if ($photo_module_instance)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters();
                    $parameters->set_training_id($training->get_id());

                    $is_allowed = \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: is_allowed(
                        \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                        $photo_module_instance->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {

                        $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex'),
                            $url,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex');
                    }
                    else
                    {
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16_na',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex'),
                            null,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex');
                    }
                }

                $training_results_module_instance = \Ehb\Application\Discovery\Module :: exists(
                    'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex',
                    array('data_source' => $data_source));

                if ($training_results_module_instance)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Parameters();
                    $parameters->set_training_id($training->get_id());
                    $parameters->set_source($training->get_source());

                    $is_allowed = \Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights :: is_allowed(
                        \Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights :: VIEW_RIGHT,
                        $training_results_module_instance->get_id(),
                        $parameters);

                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($training_results_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex'),
                            $url,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex');
                    }
                    else
                    {
                        $buttons[] = Theme :: getInstance()->getImage(
                            'Logo/16_na',
                            'png',
                            Translation :: get(
                                'TypeName',
                                null,
                                'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex'),
                            null,
                            ToolbarItem :: DISPLAY_ICON,
                            false,
                            'Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex');
                    }
                }
                $row[] = implode(PHP_EOL, $buttons);
            }

            $data[] = $row;
        }

        $table = new SortableTable($data);

        $table->setColumnHeader(0, Translation :: get('Name'), false);
        $table->setColumnHeader(1, Translation :: get('Domain'), false);
        $table->setColumnHeader(2, Translation :: get('Credits'), false);
        $table->setColumnHeader(3, '', false);
        $table->setColumnHeader(4, '', false);

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
