<?php
namespace Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\TrainingResults\DataManager;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Module;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Parameters;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    /*
     * (non-PHPdoc) @see application\discovery\module\training_results.Module::render()
     */
    public function render()
    {
        if (! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $html = array();
        $html[] = $this->get_training_properties_table() . '</br>';
        $html[] = $this->get_training_results_table();
        
        \Ehb\Application\Discovery\Rendition\View\Html\HtmlDefaultRendition::add_export_action($this);
        
        return implode(PHP_EOL, $html);
    }

    public function get_training_properties_table()
    {
        $training = DataManager::getInstance($this->get_module_instance())->retrieve_training(
            Module::get_training_info_parameters());
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        
        $faculty_info_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $training_info_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $html = array();
        $properties = array();
        $properties[Translation::get('Year')] = $training->get_year();
        
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $training->get_year()));
        
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
                    
                    $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                        \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                        $training_info_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $multi_history[] = '<a href="' . $link . '">' . $year_training->get_name() . '</a>';
                    }
                    else
                    {
                        $multi_history[] = $year_training->get_name();
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
                $year_training = $year_trainings[0];
                
                $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                
                if ($year_training->has_previous_references() && ! $year_training->has_previous_references(true))
                {
                    if ($i == 1)
                    {
                        $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $training_info_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $previous_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                     $year_training->get_name() . '</a>');
                        }
                        else
                        {
                            $previous_history = array($year, 

                            $year_training->get_name());
                        }
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $training_info_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $next_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                     $year_training->get_name() . '</a>');
                        }
                        else
                        {
                            $next_history = array($year, 

                            $year_training->get_name());
                        }
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        
                        $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $training_info_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                            $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                 $year_training->get_year() . '</a>';
                        }
                        else
                        {
                            $history[] = $year_training->get_year();
                        }
                    }
                }
                elseif ($year_training->has_next_references() && ! $year_training->has_next_references(true))
                {
                    if ($i == 1)
                    {
                        $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $training_info_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $previous_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                     $year_training->get_name() . '</a>');
                        }
                        else
                        {
                            $previous_history = array($year, 

                            $year_training->get_name());
                        }
                    }
                    elseif ($i == count($year_trainings))
                    {
                        $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $training_info_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $next_history = array(
                                $year, 
                                '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                     $year_training->get_name() . '</a>');
                        }
                        else
                        {
                            $next_history = array($year, 

                            $year_training->get_name());
                        }
                    }
                    else
                    {
                        $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                        
                        $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $training_info_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                            $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                                 $year_training->get_year() . '</a>';
                        }
                        else
                        {
                            $history[] = $year_training->get_year();
                        }
                    }
                }
                else
                {
                    $parameters = new Parameters($year_training->get_id(), $year_training->get_source());
                    
                    $is_allowed = \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::is_allowed(
                        \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                        $training_info_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
                        $history[] = '<a href="' . $link . '" title="' . $year_training->get_name() . '">' .
                             $year_training->get_year() . '</a>';
                    }
                    else
                    {
                        $history[] = $year_training->get_year();
                    }
                }
            }
            $i ++;
        }
        
        $properties[Translation::get('History')] = implode('  |  ', $history);
        
        if ($previous_history)
        {
            $properties[Translation::get(
                'HistoryWas', 
                array('YEAR' => $previous_history[0]), 
                'Ehb\Application\Discovery')] = $previous_history[1];
        }
        
        if ($next_history)
        {
            $properties[Translation::get(
                'HistoryBecomes', 
                array('YEAR' => $next_history[0]), 
                'Ehb\Application\Discovery')] = $next_history[1];
        }
        
        if ($faculty_info_module_instance)
        {
            $parameters = new \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Parameters(
                $training->get_faculty_id(), 
                $training->get_source());
            
            $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights::is_allowed(
                \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                $faculty_info_module_instance->get_id(), 
                $parameters);
            
            if ($is_allowed)
            {
                $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                $properties[Translation::get('Faculty')] = '<a href="' . $url . '">' . $training->get_faculty() . '</a>';
            }
            else
            {
                $properties[Translation::get('Faculty')] = $training->get_faculty();
            }
            BreadcrumbTrail::getInstance()->add(new Breadcrumb($url, $training->get_faculty()));
        }
        else
        {
            $properties[Translation::get('Faculty')] = $training->get_faculty();
            BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $training->get_faculty()));
        }
        
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $training->get_name()));
        
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode(PHP_EOL, $html);
    }

    public function get_training_results_table()
    {
        $html = array();
        
        $table_data = $this->get_table_data();
        if (count($table_data) > 0)
        {
            $table = new SortableTable($this->get_table_data());
            
            foreach ($this->get_table_headers() as $header_id => $header)
            {
                $table->setColumnHeader($header_id, $header[0], false);
                
                if ($header[1])
                {
                    $table->getHeader()->setColAttributes($header_id, $header[1]);
                }
            }
            
            $html[] = $table->toHTML();
        }
        
        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return multitype:multitype:string
     */
    public function get_table_data()
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $profile_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($this->get_training_results() as $enrollment)
        {
            $row = array();
            $user = \Chamilo\Core\User\Storage\DataManager::retrieve_user_by_official_code($enrollment->get_person_id());
            
            if ($profile_module_instance)
            {
                if ($user)
                {
                    $parameters = new \Ehb\Application\Discovery\Module\Profile\Parameters($user->get_id());
                    
                    $is_allowed = \Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex\Rights::is_allowed(
                        \Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                        $profile_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($profile_module_instance->get_id(), $parameters);
                        $row[] = '<a href="' . $url . '">' . $user->get_fullname() . '</a>';
                    }
                    else
                    {
                        $row[] = $user->get_fullname();
                    }
                }
                else
                {
                    $row[] = $enrollment->get_optional_property('first_name') . ' ' .
                         $enrollment->get_optional_property('last_name');
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
                    $row[] = $enrollment->get_optional_property('first_name') . ' ' .
                         $enrollment->get_optional_property('last_name');
                }
            }
            
            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();
            
            $row[] = Translation::get(
                $enrollment->get_contract_type_string(), 
                null, 
                'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex');
            if ($enrollment->is_special_result())
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath(
                    'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex') . 'ResultType/' .
                     $enrollment->get_result() . '.png" alt="' .
                     Translation::get(
                        $enrollment->get_result_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex') . '" title="' . Translation::get(
                        $enrollment->get_result_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex') . '" />';
                $row[] = $image;
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get(
                        $enrollment->get_result_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex'), 
                    Translation::get(
                        'ResultType', 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex'));
            }
            else
            {
                $row[] = ' ';
            }
            if ($enrollment->has_distinction())
            {
                $image = '<img src="' . Theme::getInstance()->getImagesPath(
                    'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex') . 'DistinctionType/' .
                     $enrollment->get_distinction() . '.png" alt="' .
                     Translation::get(
                        $enrollment->get_distinction_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex') . '" title="' . Translation::get(
                        $enrollment->get_distinction_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex') . '" />';
                $row[] = $image;
                LegendTable::getInstance()->addSymbol(
                    $image, 
                    Translation::get(
                        $enrollment->get_distinction_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex'), 
                    Translation::get(
                        'DistinctionType', 
                        null, 
                        'Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex'));
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
    public function get_table_headers()
    {
        $headers = array();
        $headers[] = array(Translation::get('Name'));
        $headers[] = array(Translation::get('Option'));
        $headers[] = array(Translation::get('Trajectory'));
        $headers[] = array(Translation::get('Contract'));
        $headers[] = array('');
        $headers[] = array('');
        
        return $headers;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }
}
