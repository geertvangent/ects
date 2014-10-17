<?php
namespace application\discovery\module\training_results\implementation\bamaflex;

use application\discovery\module\training_results\DataManager;
use libraries\utilities\StringUtilities;
use libraries\platform\Translation;
use libraries\format\Display;
use PHPExcel;

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

        $this->php_excel = new PHPExcel();
        $this->php_excel->removeSheetByIndex(0);

        $this->process_training_results();

        return \application\discovery\XlsxDefaultRendition :: save(
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

        \application\discovery\XlsxDefaultRendition :: set_headers($this->php_excel, $headers);

        $row = 2;

        foreach ($this->get_training_results() as $enrollment)
        {
            $column = 0;

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                StringUtilities :: transcode_string($enrollment->get_optional_property('last_name')));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                StringUtilities :: transcode_string($enrollment->get_optional_property('first_name')));

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
                    Translation :: get(
                        $enrollment->get_contract_type_string(),
                        null,
                        'application\discovery\module\enrollment\implementation\bamaflex')));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                StringUtilities :: transcode_string(
                    Translation :: get(
                        $enrollment->get_result_string(),
                        null,
                        'application\discovery\module\enrollment\implementation\bamaflex')));

            if ($enrollment->get_distinction_string())
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string(
                        Translation :: get(
                            $enrollment->get_distinction_string(),
                            null,
                            'application\discovery\module\enrollment\implementation\bamaflex')));
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
        return \application\discovery\Rendition :: FORMAT_XLSX;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
