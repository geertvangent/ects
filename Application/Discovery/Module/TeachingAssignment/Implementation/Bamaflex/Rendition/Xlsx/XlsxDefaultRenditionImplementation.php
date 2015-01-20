<?php
namespace Chamilo\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rendition\Xlsx;

use Chamilo\Libraries\Utilities\String\StringUtilities;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Display;
use Chamilo\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rights;
use Chamilo\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rendition\RenditionImplementation;

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

        $this->php_excel = new \PHPExcel();
        $this->php_excel->removeSheetByIndex(0);

        $this->process_teaching_assignments();

        return \Chamilo\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: save(
            $this->php_excel,
            $this->get_module());
    }

    public function process_teaching_assignments()
    {
        $headers = array();
        $headers[] = Translation :: get('Faculty');
        $headers[] = Translation :: get('Training');
        $headers[] = Translation :: get('Manager');
        $headers[] = Translation :: get('Teacher');
        $headers[] = Translation :: get('Name');
        $headers[] = Translation :: get('Credits');
        $headers[] = Translation :: get('Timeframe');

        foreach ($this->get_years() as $key => $year)
        {
            $row = 1;
            $this->php_excel->createSheet($key);
            $this->php_excel->setActiveSheetIndex($key);
            $this->php_excel->getActiveSheet()->setTitle($year);

            $this->php_excel->getActiveSheet()->getStyle(
                'A:' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_LEFT);
            $this->php_excel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
            $row ++;

            \Chamilo\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: set_headers(
                $this->php_excel,
                $headers);

            $parameters = $this->get_module_parameters();
            $parameters->set_year($year);

            $teaching_assignments = $this->get_teaching_assignments_data($parameters);

            foreach ($teaching_assignments as $key => $teaching_assignment)
            {
                $column = 0;

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string($teaching_assignment->get_faculty()));

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string($teaching_assignment->get_training()));

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string(Translation :: get($teaching_assignment->get_manager_type())));

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string(Translation :: get($teaching_assignment->get_teacher_type())));

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string($teaching_assignment->get_name()));

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string($teaching_assignment->get_credits()));

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string(Translation :: get($teaching_assignment->get_timeframe())));

                $row ++;
            }
        }
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
