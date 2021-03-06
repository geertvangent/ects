<?php
namespace Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rendition\Xlsx;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\File\Export\Excel\ExcelExport;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\CourseResults\DataManager;
use Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Module;
use Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rights;

class XlsxDefaultRenditionImplementation extends RenditionImplementation
{

    /**
     *
     * @var \PHPExcel
     */
    private $php_excel;

    public function render()
    {
        if (! Rights::is_allowed(
            Rights::VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            throw new NotAllowedException(false);
        }
        
        $this->php_excel = new \PHPExcel();
        $this->php_excel->removeSheetByIndex(0);
        
        $this->process_course_results();
        
        return \Ehb\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition::save(
            $this->php_excel, 
            $this->get_module(), 
            $this->get_file_name());
    }

    public function get_file_name()
    {
        $course = DataManager::getInstance($this->get_module_instance())->retrieve_course(
            Module::get_course_parameters());
        
        return $course->get_name() . ' ' . Translation::get('TypeName', null, $this->get_module_instance()->get_type()) .
             ' ' . $course->get_year();
    }

    public function process_course_results()
    {
        $this->php_excel->createSheet(0);
        $this->php_excel->setActiveSheetIndex(0);
        $this->php_excel->getActiveSheet()->setTitle(Translation::get('TypeName'));
        
        $headers = array();
        $headers[] = Translation::get('LastName');
        $headers[] = Translation::get('FirstName');
        $headers[] = Translation::get('TrajectoryType');
        
        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = $mark_moment->get_name();
            $headers[] = Translation::get('MarkStatus', array('TRY' => $mark_moment->get_name()));
        }
        
        $this->php_excel->getActiveSheet()->getStyle('A:' . \PHPExcel_Cell::stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
            \PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        \Ehb\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition::set_headers($this->php_excel, $headers);
        
        $row = 2;
        
        foreach ($this->get_course_results() as $course_result)
        {
            $column = 0;
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++, 
                $row, 
                ExcelExport::transcode_string($course_result->get_person_last_name()));
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++, 
                $row, 
                ExcelExport::transcode_string($course_result->get_person_first_name()));
            
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++, 
                $row, 
                ExcelExport::transcode_string(
                    Translation::get(
                        $course_result->get_trajectory_type_string(), 
                        null, 
                        'Ehb\Application\Discovery\Module\enrollment\Implementation\Bamaflex')));
            
            foreach ($this->get_mark_moments() as $mark_moment)
            {
                $mark = $course_result->get_mark_by_moment_id($mark_moment->get_id());
                
                if ($mark->get_result())
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        ExcelExport::transcode_string($mark->get_visual_result()));
                }
                elseif ($mark->get_sub_status())
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        ExcelExport::transcode_string($mark->get_sub_status()));
                }
                else
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
                }
                
                if ($mark->get_status())
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++, 
                        $row, 
                        ExcelExport::transcode_string(
                            Translation::get(
                                $mark->get_status_string(), 
                                null, 
                                'Ehb\Application\Discovery\Module\career\Implementation\Bamaflex')));
                }
                else
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
                }
            }
            
            $row ++;
        }
        
        $this->php_excel->getActiveSheet()->calculateColumnWidths();
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_XLSX;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }
}
