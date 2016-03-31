<?php
namespace Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\Xlsx;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\File\Export\Excel\ExcelExport;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\TrainingResults\DataManager;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Module;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights;

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
            throw new NotAllowedException(false);
        }

        $this->php_excel = new \PHPExcel();
        $this->php_excel->removeSheetByIndex(0);

        $this->process_training_results();

        return \Ehb\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: save(
            $this->php_excel,
            $this->get_module(),
            $this->get_file_name());
    }

    public function get_file_name()
    {
        $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
            Module :: get_training_info_parameters());

        return $training->get_name() . ' ' .
             Translation :: get('TypeName', null, $this->get_module_instance()->get_type()) . ' ' . $training->get_year();
    }

    public function process_training_results()
    {
        $this->php_excel->createSheet(0);
        $this->php_excel->setActiveSheetIndex(0);
        $this->php_excel->getActiveSheet()->setTitle(Translation :: get('TypeName'));

        $headers = array();
        $headers[] = Translation :: get('LastName');
        $headers[] = Translation :: get('FirstName');
        $headers[] = Translation :: get('Option');
        $headers[] = Translation :: get('Trajectory');
        $headers[] = Translation :: get('Contract');
        $headers[] = Translation :: get('ResultType');
        $headers[] = Translation :: get('DistinctionType');

        $this->php_excel->getActiveSheet()->getStyle(
            'A:' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
            \PHPExcel_Style_Alignment :: HORIZONTAL_LEFT);

        \Ehb\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: set_headers($this->php_excel, $headers);

        $row = 2;

        foreach ($this->get_training_results() as $enrollment)
        {
            $column = 0;

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_optional_property('last_name')));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_optional_property('first_name')));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_unified_option()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_unified_trajectory()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string(
                    Translation :: get(
                        $enrollment->get_contract_type_string(),
                        null,
                        'Ehb\Application\Discovery\Module\enrollment\Implementation\Bamaflex')));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string(
                    Translation :: get(
                        $enrollment->get_result_string(),
                        null,
                        'Ehb\Application\Discovery\Module\enrollment\Implementation\Bamaflex')));

            if ($enrollment->get_distinction_string())
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    ExcelExport :: transcode_string(
                        Translation :: get(
                            $enrollment->get_distinction_string(),
                            null,
                            'Ehb\Application\Discovery\Module\enrollment\Implementation\Bamaflex')));
            }
            else
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row, '-');
            }

            $row ++;
        }
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: FORMAT_XLSX;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
