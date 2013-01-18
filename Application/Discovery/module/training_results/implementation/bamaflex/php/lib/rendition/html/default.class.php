<?php
namespace application\discovery\module\training_results\implementation\bamaflex;

use application\discovery\LegendTable;
use common\libraries\Theme;
use application\discovery\SortableTable;
use common\libraries\PropertiesTable;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\Translation;
use common\libraries\Display;
use application\discovery\module\training_results\DataManager;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{
    /*
     * (non-PHPdoc) @see application\discovery\module\training_results.Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();

        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities,
                $this->get_module_instance()->get_id(), $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        $html = array();
        $html[] = $this->get_training_properties_table() . '</br>';
        $html[] = $this->get_training_results_table();

        \application\discovery\HtmlDefaultRendition ::  add_export_action($this);

        return implode("\n", $html);
    }

    function get_training_properties_table()
    {
        $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                Module :: get_training_info_parameters());

        $data_source = $this->get_module_instance()->get_setting('data_source');

        $faculty_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\faculty_info\implementation\bamaflex',
                array('data_source' => $data_source));

        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $training->get_year();

        $history = array();
        $trainings = $training->get_all($this->get_module_instance());

        $i = 1;
        foreach ($trainings as $year => $year_trainings)
        {
            if (count($year_trainings) > 1)
            {
                $multi_history = array();

                foreach ($year_trainings as $year_training)
                {
                    $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                    $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                    $multi_history[] = '<a href="' . $link . '">' . $year_training->get_name() . '</a>';
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
                $year_training = $year_trainings[0];

                $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);

                if ($year_training->has_previous_references() && ! $year_training->has_previous_references(true))
                {
                    if ($i == 1)
                    {
                        $previous_history = array($year,
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $next_history = array($year,
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_year() . '</a>';
                    }
                }
                elseif ($year_training->has_next_references() && ! $year_training->has_next_references(true))
                {
                    if ($i == 1)
                    {
                        $previous_history = array($year,
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $next_history = array($year,
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_name() . '</a>');
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_year() . '</a>';
                    }
                }
                else
                {
                    $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                    $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                    $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' . $year_training->get_year() . '</a>';
                }
            }
            $i ++;
        }

        $properties[Translation :: get('History')] = implode('  |  ', $history);

        if ($previous_history)
        {
            $properties[Translation :: get('HistoryWas', array('YEAR' => $previous_history[0]), 'application\discovery')] = $previous_history[1];
        }

        if ($next_history)
        {
            $properties[Translation :: get('HistoryBecomes', array('YEAR' => $next_history[0]), 'application\discovery')] = $next_history[1];
        }

        if ($faculty_info_module_instance)
        {
            $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters(
                    $training->get_faculty_id(), $training->get_source());
            $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Faculty')] = '<a href="' . $url . '">' . $training->get_faculty() . '</a>';
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb($url, $training->get_faculty()));
        }
        else
        {
            $properties[Translation :: get('Faculty')] = $training->get_faculty();
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_faculty()));
        }

        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_name()));

        $table = new PropertiesTable($properties);

        $html[] = $table->toHtml();
        return implode("\n", $html);
    }

    function get_training_results_table()
    {
        $html = array();

        $table_data = $this->get_table_data();
        if (count($table_data) > 0)
        {
            $table = new SortableTable($this->get_table_data());

            foreach ($this->get_table_headers() as $header_id => $header)
            {
                $table->set_header($header_id, $header[0], false);

                if ($header[1])
                {
                    $table->getHeader()->setColAttributes($header_id, $header[1]);
                }
            }

            $html[] = $table->toHTML();
        }

        return implode("\n", $html);
    }

    /**
     *
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $profile_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\profile\implementation\bamaflex', array('data_source' => $data_source));

        foreach ($this->get_training_results() as $enrollment)
        {
            $row = array();
            $user = \user\DataManager :: retrieve_user_by_official_code($enrollment->get_person_id());

            if ($profile_module_instance)
            {
                if ($user)
                {
                    $parameters = new \application\discovery\module\profile\Parameters($user->get_id());
                    $url = $this->get_instance_url($profile_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $user->get_fullname() . '</a>';
                }
                else
                {
                    $row[] = $enrollment->get_optional_property('first_name') . ' ' . $enrollment->get_optional_property(
                            'last_name');
                }
            }
            else
            {
                if ($user)
                {
                    $row[] = $user->get_fullname();
                }
                else
                {
                    $row[] = $enrollment->get_optional_property('first_name') . ' ' . $enrollment->get_optional_property(
                            'last_name');
                }
            }

            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();

            $row[] = Translation :: get($enrollment->get_contract_type_string(), null,
                    'application\discovery\module\enrollment\implementation\bamaflex');
            if ($enrollment->is_special_result())
            {
                $image = '<img src="' . Theme :: get_image_path(
                        'application\discovery\module\enrollment\implementation\bamaflex') . 'result_type/' . $enrollment->get_result() . '.png" alt="' . Translation :: get(
                        $enrollment->get_result_string(), null,
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" title="' . Translation :: get(
                        $enrollment->get_result_string(), null,
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image,
                        Translation :: get($enrollment->get_result_string(), null,
                                'application\discovery\module\enrollment\implementation\bamaflex'),
                        Translation :: get('ResultType', null,
                                'application\discovery\module\enrollment\implementation\bamaflex'));
            }
            else
            {
                $row[] = ' ';
            }
            if ($enrollment->has_distinction())
            {
                $image = '<img src="' . Theme :: get_image_path(
                        'application\discovery\module\enrollment\implementation\bamaflex') . 'distinction_type/' . $enrollment->get_distinction() . '.png" alt="' . Translation :: get(
                        $enrollment->get_distinction_string(), null,
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" title="' . Translation :: get(
                        $enrollment->get_distinction_string(), null,
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image,
                        Translation :: get($enrollment->get_distinction_string(), null,
                                'application\discovery\module\enrollment\implementation\bamaflex'),
                        Translation :: get('DistinctionType', null,
                                'application\discovery\module\enrollment\implementation\bamaflex'));
            }
            else
            {
                $row[] = ' ';
            }

            $data[] = $row;
        }

        return $data;
    }

    /**
     *
     * @return multitype:string
     */
    function get_table_headers()
    {
        $headers = array();
        $headers[] = array(Translation :: get('Name'));
        $headers[] = array(Translation :: get('Option'));
        $headers[] = array(Translation :: get('Trajectory'));
        $headers[] = array(Translation :: get('Contract'));
        $headers[] = array('');
        $headers[] = array('');

        return $headers;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
?>