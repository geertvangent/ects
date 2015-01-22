<?php
namespace Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Rendition\Xlsx;

use Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Course;
use Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Rights;
use Chamilo\Application\Discovery\Module\Enrollment\DataManager;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\StringUtilities;

class XlsxDefaultRenditionImplementation extends RenditionImplementation
{

    /**
     *
     * @var \PHPExcel
     */
    private $php_excel;

    public function render()
    {
        if (! Rights :: is_allowed(
            Rights :: VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        
        $this->result_right = Rights :: is_allowed(
            Rights :: RESULT_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters());
        
        $this->php_excel = new \PHPExcel();
        $this->php_excel->getDefaultStyle()->getFont()->setName('DejaVu Sans');
        $this->php_excel->removeSheetByIndex(0);
        
        if ($this->has_data())
        {
            $this->process_enrollment_courses();
        }
        
        return \Chamilo\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: save(
            $this->php_excel, 
            $this->get_module());
    }

    public function process_enrollment_courses()
    {
        $contracts = $this->get_contracts();
        
        $this->php_excel->createSheet(0);
        $this->php_excel->setActiveSheetIndex(0);
        $this->php_excel->getActiveSheet()->setTitle(Translation :: get('Index'));
        
        $row = 1;
        $worksheet_key = 1;
        
        $headers = array();
        $headers[] = '#';
        $headers[] = Translation :: get('Training');
        $headers[] = Translation :: get('Option');
        $headers[] = Translation :: get('Result');
        
        \Chamilo\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: set_headers(
            $this->php_excel, 
            $headers, 
            $row);
        $row ++;
        
        foreach ($contracts as $contract)
        {
            $last_enrollment = $contract[0];
            
            $column = 0;
            
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++, 
                $row, 
                StringUtilities :: transcode_string($worksheet_key));
            
            if ($last_enrollment->get_contract_id())
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++, 
                    $row, 
                    StringUtilities :: transcode_string($last_enrollment->get_training()));
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++, 
                    $row, 
                    StringUtilities :: transcode_string($last_enrollment->get_unified_option()));
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++, 
                    $row, 
                    StringUtilities :: transcode_string(Translation :: get($last_enrollment->get_result_string())));
            }
            else
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++, 
                    $row, 
                    StringUtilities :: transcode_string(Translation :: get('Various')));
            }
            
            $row ++;
            $worksheet_key ++;
        }
        
        $worksheet_key = 1;
        
        foreach ($contracts as $contract)
        {
            $this->php_excel->createSheet($worksheet_key);
            $this->php_excel->setActiveSheetIndex($worksheet_key);
            
            $row = 1;
            
            $headers = array();
            
            $headers[] = Translation :: get('Year');
            $headers[] = Translation :: get('Credits');
            $headers[] = Translation :: get('Type');
            $headers[] = Translation :: get('ProgrammeType');
            $headers[] = Translation :: get('Course');
            $headers[] = Translation :: get('FirstMark');
            $headers[] = Translation :: get('FirstMarkStatus');
            $headers[] = Translation :: get('SecondMark');
            $headers[] = Translation :: get('SecondMarkStatus');
            $headers[] = Translation :: get('EnrollmentYear');
            $headers[] = Translation :: get('Training');
            $headers[] = Translation :: get('Option');
            $headers[] = Translation :: get('Trajectory');
            $headers[] = Translation :: get('ResultType');
            $headers[] = Translation :: get('ContractType');
            
            $this->php_excel->getActiveSheet()->getStyle(
                'A:' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_LEFT);
            
            $this->php_excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
            
            \Chamilo\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: set_headers(
                $this->php_excel, 
                $headers, 
                $row);
            $row ++;
            
            foreach ($contract as $enrollment)
            {
                $training = $enrollment->get_training_object();
                
                $courses = $this->get_module()->get_data_manager()->retrieve_courses(array($enrollment->get_id()));
                
                foreach ($courses[$enrollment->get_id()] as $course)
                {
                    $column = 0;
                    
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string($course->get_year()));
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string($course->get_credits()));
                    
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string(Translation :: get($course->get_type_string())));
                    
                    if ($course->has_children())
                    {
                        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                            $column ++, 
                            $row, 
                            StringUtilities :: transcode_string(
                                Translation :: get(Course :: programme_type_string(Course :: PROGRAMME_TYPE_COMPLEX))));
                    }
                    else
                    {
                        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                            $column ++, 
                            $row, 
                            StringUtilities :: transcode_string(
                                Translation :: get(Course :: programme_type_string(Course :: PROGRAMME_TYPE_SIMPLE))));
                    }
                    
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string(Translation :: get($course->get_name())));
                    
                    foreach ($this->get_mark_moments() as $mark_moment)
                    {
                        $mark = $course->get_mark_by_moment_id($mark_moment->get_id());
                        
                        if ($mark->get_publish_status() == 1 || ! $training->is_current() || $this->result_right)
                        {
                            if ($mark->get_result())
                            {
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                    $column ++, 
                                    $row, 
                                    StringUtilities :: transcode_string(Translation :: get($mark->get_visual_result())));
                            }
                            elseif ($mark->get_sub_status())
                            {
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                    $column ++, 
                                    $row, 
                                    StringUtilities :: transcode_string(Translation :: get($mark->get_sub_status())));
                            }
                            else
                            {
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
                            }
                            
                            if ($mark->get_status())
                            {
                                if ($mark->is_abandoned())
                                {
                                    $mark_status = Translation :: get($mark->get_status_string() . 'Abandoned');
                                }
                                else
                                {
                                    $mark_status = Translation :: get($mark->get_status_string());
                                }
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                    $column ++, 
                                    $row, 
                                    StringUtilities :: transcode_string($mark_status));
                            }
                            else
                            {
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
                            }
                        }
                        else
                        {
                            if ($mark->get_result())
                            {
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                    $column ++, 
                                    $row, 
                                    StringUtilities :: transcode_string(Translation :: get('ResultNotYetAvailable')));
                            }
                            else
                            {
                                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
                            }
                            
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
                        }
                    }
                    
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string($enrollment->get_year()));
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string($enrollment->get_training()));
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string($enrollment->get_unified_option()));
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string($enrollment->get_unified_trajectory()));
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string(Translation :: get($enrollment->get_result_string())));
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        StringUtilities :: transcode_string(Translation :: get($enrollment->get_contract_type_string())));
                    
                    $row ++;
                    
                    if ($course->has_children())
                    {
                        foreach ($course->get_children() as $child)
                        {
                            $column = 0;
                            
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($child->get_year()));
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($child->get_credits()));
                            
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string(Translation :: get($child->get_type_string())));
                            
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string(
                                    Translation :: get(Course :: programme_type_string(Course :: PROGRAMME_TYPE_PART))));
                            
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($child->get_name()));
                            
                            $added = false;
                            foreach ($this->get_mark_moments() as $mark_moment)
                            {
                                $mark = $child->get_mark_by_moment_id($mark_moment->get_id());
                                
                                if ($mark->get_publish_status() == 1 || ! $training->is_current() || $this->result_right)
                                {
                                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                        $column ++, 
                                        $row, 
                                        StringUtilities :: transcode_string($mark->get_result()));
                                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                        $column ++, 
                                        $row, 
                                        '-');
                                }
                                else
                                {
                                    if ($mark->get_result())
                                    {
                                        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                            $column ++, 
                                            $row, 
                                            StringUtilities :: transcode_string(
                                                Translation :: get('ResultNotYetAvailable')));
                                    }
                                    else
                                    {
                                        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                            $column ++, 
                                            $row, 
                                            '-');
                                    }
                                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                        $column ++, 
                                        $row, 
                                        '-');
                                }
                            }
                            
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($enrollment->get_year()));
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($enrollment->get_training()));
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($enrollment->get_unified_option()));
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string($enrollment->get_unified_trajectory()));
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string(
                                    Translation :: get($enrollment->get_result_string())));
                            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                                $column ++, 
                                $row, 
                                StringUtilities :: transcode_string(
                                    Translation :: get($enrollment->get_contract_type_string())));
                            
                            $range = 'A' . $row . ':' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1) .
                                 $row;
                            $this->php_excel->getActiveSheet()->getStyle($range)->getFont()->setItalic(true);
                            $this->php_excel->getActiveSheet()->getStyle($range)->getFont()->getColor()->setRGB(
                                'AAAAAA');
                            
                            $row ++;
                        }
                    }
                }
            }
            
            $this->php_excel->getActiveSheet()->setTitle((string) $worksheet_key);
            $worksheet_key ++;
        }
    }

    public function get_contracts()
    {
        $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments(
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
        return \Chamilo\Application\Discovery\Rendition\Rendition :: FORMAT_XLSX;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
