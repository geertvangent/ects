<?php
namespace Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Module\Career\DataManager;
use Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex\Course;
use Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex\Rights;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    private $result_right;

    private $credits = array();

    public function render()
    {
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, Translation::get(TypeName)));
        
        if (! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $this->result_right = Rights::is_allowed(
            Rights::RESULT_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters());
        
        $html = array();
        
        if ($this->has_data())
        {
            $contract = $this->get_module_parameters()->get_contract_id();
            
            if (is_null($contract))
            {
                
                $contracts = $this->get_contracts();
                $last_enrollment = array_shift($contracts);
                $last_enrollment = $last_enrollment[0];
                $contract = ($last_enrollment->get_contract_id() ? $last_enrollment->get_contract_id() : 0);
            }
            
            $html[] = $this->get_enrollment_courses($contract);
        }
        else
        {
            $html[] = Display::normal_message(Translation::get('NoData'), true);
        }
        
        \Ehb\Application\Discovery\Rendition\View\Html\HtmlDefaultRendition::add_export_action($this);
        
        return implode(PHP_EOL, $html);
    }

    public function get_enrollment_courses($selected_contract)
    {
        $html = array();
        
        $contracts = $this->get_contracts();
        
        $tabs = new DynamicVisualTabsRenderer('contract_list');
        
        foreach ($contracts as $contract)
        {
            $last_enrollment = $contract[0];
            
            if ($last_enrollment->get_contract_id())
            {
                $tab_name = array();
                if ($last_enrollment->is_special_result())
                {
                    $tab_image_path = Theme::getInstance()->getImagesPath(
                        'Ehb/Application/Discovery/Module/Enrollment/Implementation/Bamaflex') . 'ResultType/' .
                         $last_enrollment->get_result() . '.png';
                    
                    $tab_image = '<img src="' . $tab_image_path . '" alt="' .
                         Translation::get($last_enrollment->get_result_string()) . '" title="' .
                         Translation::get($last_enrollment->get_result_string()) . '" />';
                    LegendTable::getInstance()->addSymbol(
                        $tab_image, 
                        Translation::get($last_enrollment->get_result_string()), 
                        Translation::get('ResultType'));
                }
                else
                {
                    $tab_image_path = null;
                }
                
                $tab_name[] = $last_enrollment->get_training();
                
                if ($last_enrollment->get_unified_option())
                {
                    $tab_name[] = $last_enrollment->get_unified_option();
                }
                $tab_name = implode(' | ', $tab_name);
            }
            else
            {
                $tab_image_path = Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'various.png';
                $tab_name = Translation::get('Various');
            }
            
            $parameters = $this->module_parameters();
            $last_contract_id = ($last_enrollment->get_contract_id() ? $last_enrollment->get_contract_id() : 0);
            $parameters->set_contract_id($last_contract_id);
            
            $tabs->add_tab(
                new DynamicVisualTab(
                    'contract_' . $last_enrollment->get_contract_id() . '', 
                    $tab_name, 
                    $tab_image_path, 
                    $this->get_module()->get_instance_url(
                        $this->get_module()->get_module_instance()->get_id(), 
                        $parameters), 
                    $last_contract_id == $selected_contract ? true : false));
        }
        
        $contract = $contracts[$selected_contract];
        
        $enrollment_ids = array();
        foreach ($contract as $enrollment)
        {
            $enrollment_ids[] = $enrollment->get_id();
        }
        
        $last_enrollment = $contract[0];
        $contract_html = array();
        
        foreach ($contract as $enrollment)
        {
            $contract_html[] = '<table class="data_table" id="tablename"><thead><tr><th class="action">';
            
            if ($enrollment->is_special_result())
            {
                $tab_image_path = Theme::getInstance()->getImagesPath(
                    'Ehb/Application/Discovery/Module/Enrollment/Implementation/Bamaflex') . 'ResultType/' .
                     $enrollment->get_result() . '.png';
                $tab_image = '<img src="' . $tab_image_path . '" alt="' .
                     Translation::get($enrollment->get_result_string()) . '" title="' .
                     Translation::get($enrollment->get_result_string()) . '" />';
                $contract_html[] = $tab_image;
                LegendTable::getInstance()->addSymbol(
                    $tab_image, 
                    Translation::get($enrollment->get_result_string()), 
                    Translation::get('ResultType'));
            }
            
            $contract_html[] = '</th><th class="action">';
            $tab_image_path = Theme::getInstance()->getImagePath(
                'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'ContractType/' .
                 $enrollment->get_contract_type() . '.png';
            
            $tab_image = '<img src="' . $tab_image_path . '" alt="' .
                 Translation::get($enrollment->get_contract_type_string()) . '" title="' .
                 Translation::get($enrollment->get_contract_type_string()) . '" />';
            $contract_html[] = $tab_image;
            LegendTable::getInstance()->addSymbol(
                $tab_image, 
                Translation::get($enrollment->get_contract_type_string()), 
                Translation::get('ContractType'));
            
            $contract_html[] = '</th><th>';
            
            $enrollment_name = array();
            
            $enrollment_name[] = $enrollment->get_year();
            $enrollment_name[] = $enrollment->get_training();
            
            if ($enrollment->get_unified_option())
            {
                $enrollment_name[] = $enrollment->get_unified_option();
            }
            
            if ($enrollment->get_unified_trajectory())
            {
                $enrollment_name[] = $enrollment->get_unified_trajectory();
            }
            
            $contract_html[] = implode(' | ', $enrollment_name);
            $contract_html[] = '</th></tr></thead></table>';
            $contract_html[] = '<br />';
            
            $table = new SortableTable($this->get_table_data($enrollment_ids, $enrollment));
            
            foreach ($this->get_table_headers() as $header_id => $header)
            {
                $table->setColumnHeader($header_id, $header[0], false);
                
                if ($header[1])
                {
                    $table->getHeader()->setColAttributes($header_id, $header[1]);
                }
            }
            
            $contract_html[] = $table->toHTML();
            $contract_html[] = '<br />';
        }
        
        $training = $last_enrollment->get_training_object();
        $table_data = array();
        $years = $this->credits[$last_enrollment->get_contract_id()];
        $total = 0;
        
        ksort($years);
        foreach ($years as $year => $types)
        {
            foreach ($types as $type => $credits)
            {
                $row = array();
                $row[] = $year;
                $row[] = '<img src="' . Theme::getInstance()->getImagePath(
                    'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'CourseType/' . $type .
                     '.png" alt="' . Translation::get(Course::type_string($type)) . '" title="' .
                     Translation::get(Course::type_string($type)) . '" />';
                $row[] = Translation::get(Course::type_string($type));
                $row[] = $credits;
                if (in_array($type, Course::get_types_for_total_credits()))
                {
                    if ($training->is_current() && $year == $training->get_year() && $type == Course::TYPE_NORMAL)
                    {
                        $row[] = '<img src="' . Theme::getInstance()->getImagePath(
                            'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'TotalType/3.png" alt="' .
                             Translation::get('CreditPending') . '" title="' . Translation::get('CreditPending') . '" />';
                    }
                    else
                    {
                        $row[] = '<img src="' . Theme::getInstance()->getImagePath(
                            'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'TotalType/1.png" alt="' .
                             Translation::get('CreditTrue') . '" title="' . Translation::get('CreditTrue') . '" />';
                        $total += $credits;
                    }
                }
                else
                {
                    $row[] = '<img src="' .
                         Theme::getInstance()->getImagePath(
                            'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'TotalType/2.png" alt="' .
                         Translation::get('CreditFalse') . '" title="' . Translation::get('CreditFalse') . '" />';
                }
                
                $table_data[] = $row;
            }
        }
        if ($last_enrollment->get_contract_id())
        {
            if ($training->get_credits())
            {
                $table_data[] = array(' ', ' ', Translation::get('Total'), $total . '/' . $training->get_credits(), ' ');
            }
            else
            {
                $table_data[] = array(' ', ' ', Translation::get('Total'), $total, ' ');
            }
        }
        
        $table = new SortableTable($table_data);
        
        $table->setColumnHeader(0, Translation::get('Year'), false);
        $table->setColumnHeader(1, '', false);
        $table->setColumnHeader(2, Translation::get('Type'), false);
        $table->setColumnHeader(3, Translation::get('Credits'), false);
        $table->setColumnHeader(4, '', false);
        
        $contract_html[] = $table->toHTML();
        $contract_html[] = '<br />';
        
        $tabs->set_content(implode(PHP_EOL, $contract_html));
        
        $html[] = $tabs->render();
        
        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @return multitype:string
     */
    public function get_table_headers()
    {
        $headers = array();
        $headers[] = array(Translation::get('Year'), 'class="code"');
        $headers[] = array(Translation::get('Credits'), 'class="action"');
        $headers[] = array('', 'class="action"');
        $headers[] = array(Translation::get('Course'));
        
        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = array($mark_moment->get_name());
            $headers[] = array();
        }
        
        return $headers;
    }

    /**
     *
     * @return multitype:multitype:string
     */
    public function get_table_data($enrollment_ids, $enrollment)
    {
        $data = array();
        $training = $enrollment->get_training_object();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \Ehb\Application\Discovery\Module::exists(
            'Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $course = $this->get_module()->get_data_manager()->retrieve_courses($enrollment_ids);
        // $course = $this->get_courses();
        
        foreach ($course[$enrollment->get_id()] as $course)
        {
            $row = array();
            $row[] = $course->get_year();
            $row[] = $course->get_credits();
            
            if ($course->is_special_type())
            {
                if (! $course->has_children() || $course->get_parent_programme_id())
                {
                    $this->credits[$enrollment->get_contract_id()][$course->get_year()][$course->get_type()] += $course->get_credits();
                }
                $course_type_image = '<img src="' . Theme::getInstance()->getImagePath(
                    'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'CourseType/' .
                     $course->get_type() . '.png" alt="' . Translation::get($course->get_type_string()) . '" title="' .
                     Translation::get($course->get_type_string()) . '" />';
                $row[] = $course_type_image;
                LegendTable::getInstance()->addSymbol(
                    $course_type_image, 
                    Translation::get($course->get_type_string()), 
                    Translation::get('CourseType'));
            }
            else
            {
                $row[] = ' ';
            }
            
            if ($course_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters(
                    $course->get_programme_id(), 
                    $course->get_source());
                
                $is_allowed = \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::is_allowed(
                    \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                    $course_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $course->get_name() . '</a>';
                }
                else
                {
                    $row[] = $course->get_name();
                }
            }
            else
            {
                $row[] = $course->get_name();
            }
            
            $added = false;
            
            foreach ($this->get_mark_moments() as $mark_moment)
            {
                $mark = $course->get_mark_by_moment_id($mark_moment->get_id());
                if ((! $course->has_children() || $course->get_parent_programme_id()) &&
                     ($mark->is_credit() || $enrollment->get_result() == Enrollment::RESULT_NO_DATA) &&
                     (! $course->is_special_type()) && ! $added)
                {
                    $added = true;
                    $this->credits[$enrollment->get_contract_id()][$course->get_year()][$course->get_type()] += $course->get_credits();
                }
                
                if ($mark->get_publish_status() == 1 || ! $training->is_current() || $this->result_right)
                {
                    if ($mark->get_result())
                    {
                        $row[] = $mark->get_visual_result();
                    }
                    else
                    {
                        $row[] = $mark->get_sub_status();
                    }
                    
                    if ($mark->get_status())
                    {
                        if ($mark->is_abandoned())
                        {
                            $mark_status_image = '<img src="' .
                                 Theme::getInstance()->getImagePath(
                                    'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'StatusType/' .
                                 $mark->get_status() . 'Na.png" alt="' .
                                 Translation::get($mark->get_status_string() . 'Abandoned') . '" title="' .
                                 Translation::get($mark->get_status_string() . 'Abandoned') . '" />';
                            LegendTable::getInstance()->addSymbol(
                                $mark_status_image, 
                                Translation::get($mark->get_status_string() . 'Abandoned'), 
                                Translation::get('MarkStatus'));
                        }
                        else
                        {
                            $mark_status_image = '<img src="' .
                                 Theme::getInstance()->getImagePath(
                                    'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'StatusType/' .
                                 $mark->get_status() . '.png" alt="' . Translation::get($mark->get_status_string()) .
                                 '" title="' . Translation::get($mark->get_status_string()) . '" />';
                            LegendTable::getInstance()->addSymbol(
                                $mark_status_image, 
                                Translation::get($mark->get_status_string()), 
                                Translation::get('MarkStatus'));
                        }
                        $row[] = $mark_status_image;
                    }
                    else
                    {
                        $row[] = null;
                    }
                }
                else
                {
                    if ($mark->get_result())
                    {
                        $result_na_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                             'mark_result_na.png" alt="' . Translation::get('ResultNotYetAvailable') . '" title="' .
                             Translation::get('ResultNotYetAvailable') . '" />';
                        $row[] = $result_na_image;
                        LegendTable::getInstance()->addSymbol(
                            $result_na_image, 
                            Translation::get('ResultNotYetAvailable'), 
                            Translation::get('MarkResult'));
                    }
                    else
                    {
                        $row[] = null;
                    }
                    $row[] = null;
                }
            }
            
            $data[] = $row;
            
            if ($course->has_children())
            {
                foreach ($course->get_children() as $child)
                {
                    $row = array();
                    $row[] = '<span class="course_child_text">' . $child->get_year() . '</span>';
                    $row[] = '<span class="course_child_text">' . $child->get_credits() . '</span>';
                    
                    if ($child->is_special_type())
                    {
                        $this->credits[$enrollment->get_contract_id()][$child->get_year()][$child->get_type()] += $child->get_credits();
                        
                        $child_type_image = '<img src="' . Theme::getInstance()->getImagePath(
                            'Ehb/Application/Discovery/Module/Career/Implementation/Bamaflex') . 'CourseType/' .
                             $child->get_type() . '.png" alt="' . Translation::get($child->get_type_string()) .
                             '" title="' . Translation::get($child->get_type_string()) . '" />';
                        $row[] = $child_type_image;
                        LegendTable::getInstance()->addSymbol(
                            $child_type_image, 
                            Translation::get($child->get_type_string()), 
                            Translation::get('CourseType'));
                    }
                    else
                    {
                        $row[] = ' ';
                    }
                    
                    if ($course_module_instance)
                    {
                        $parameters = new \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters(
                            $child->get_programme_id(), 
                            $child->get_source());
                        
                        $is_allowed = \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::is_allowed(
                            \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rights::VIEW_RIGHT, 
                            $course_module_instance->get_id(), 
                            $parameters);
                        
                        if ($is_allowed)
                        {
                            $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                            $row[] = '<span class="course_child_link"><a href="' . $url . '">' . $child->get_name() .
                                 '</a></span>';
                        }
                        else
                        {
                            $row[] = '<span class="course_child_link">' . $child->get_name() . '</span>';
                        }
                    }
                    else
                    {
                        $row[] = '<span class="course_child_link">' . $child->get_name() . '</span>';
                    }
                    
                    $added = false;
                    foreach ($this->get_mark_moments() as $mark_moment)
                    {
                        $mark = $child->get_mark_by_moment_id($mark_moment->get_id());
                        if (! $child->is_special_type() &&
                             ($mark->is_credit() || $enrollment->get_result() == Enrollment::RESULT_NO_DATA) && ! $added)
                        {
                            $added = true;
                            if ($course->is_special_type())
                            {
                                $this->credits[$enrollment->get_contract_id()][$child->get_year()][$course->get_type()] += $child->get_credits();
                            }
                            else
                            {
                                $this->credits[$enrollment->get_contract_id()][$child->get_year()][$child->get_type()] += $child->get_credits();
                            }
                        }
                        
                        if ($mark->get_publish_status() == 1 || ! $training->is_current() || $this->result_right)
                        {
                            $row[] = $mark->get_result();
                            $row[] = null;
                        }
                        else
                        {
                            if ($mark->get_result())
                            {
                                $result_na_image = '<img src="' . Theme::getInstance()->getImagesPath() .
                                     'mark_result_na.png" alt="' . Translation::get('ResultNotYetAvailable') .
                                     '" title="' . Translation::get('ResultNotYetAvailable') . '" />';
                                $row[] = $result_na_image;
                                LegendTable::getInstance()->addSymbol(
                                    $result_na_image, 
                                    Translation::get('ResultNotYetAvailable'), 
                                    Translation::get('MarkResult'));
                            }
                            else
                            {
                                $row[] = null;
                            }
                            $row[] = null;
                        }
                    }
                    
                    $data[] = $row;
                }
            }
        }
        
        return $data;
    }

    public function get_enrollments()
    {
        $enrollments = DataManager::getInstance($this->get_module_instance())->retrieve_enrollments(
            $this->get_module_parameters());
        
        $contract_type_enrollments = array();
        
        foreach ($enrollments as $enrollment)
        {
            // if ($enrollment->get_contract_type() == $contract_type)
            // {
            $contract_type_enrollments[] = $enrollment;
            
            // }
        }
        
        return $contract_type_enrollments;
    }

    public function get_contracts($enrollment)
    {
        $enrollments = DataManager::getInstance($this->get_module_instance())->retrieve_enrollments(
            $this->get_module_parameters());
        $contract_enrollments = array();
        
        foreach ($enrollments as $enrollment)
        {
            if ($enrollment->get_contract_id())
            {
                $contract_enrollments[$enrollment->get_contract_id()][] = $enrollment;
            }
            else
            {
                $contract_enrollments[0][] = $enrollment;
            }
        }
        krsort($contract_enrollments);
        
        return $contract_enrollments;
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
