<?php
namespace Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rendition\Xlsx;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\File\Export\Excel\ExcelExport;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\Enrollment\DataManager;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Enrollment;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rights;

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

        if (count($this->get_enrollments()) > 0)
        {
            $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types(
                $this->get_module_parameters());

            $this->php_excel->createSheet(0);
            $this->php_excel->setActiveSheetIndex(0);
            $this->php_excel->getActiveSheet()->setTitle(Translation :: get('AllContracts'));

            $this->process_enrollments();

            foreach ($contract_types as $key => $contract_type)
            {
                $this->php_excel->createSheet($key + 1);
                $this->php_excel->setActiveSheetIndex($key + 1);
                $this->php_excel->getActiveSheet()->setTitle(
                    Translation :: get(Enrollment :: contract_type_string($contract_type)));
                $this->process_enrollments($contract_type);
            }
        }

        return \Ehb\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: save(
            $this->php_excel,
            $this->get_module());
    }

    public function process_enrollments($contract_type = Enrollment :: CONTRACT_TYPE_ALL)
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

        $headers = array();
        $headers[] = Translation :: get('Year');
        $headers[] = Translation :: get('Faculty');
        $headers[] = Translation :: get('Training');
        $headers[] = Translation :: get('Option');
        $headers[] = Translation :: get('Trajectory');

        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $headers[] = Translation :: get('Contract');
        }

        $headers[] = Translation :: get('ResultType');
        $headers[] = Translation :: get('GenerationStudent');

        \Ehb\Application\Discovery\Rendition\View\Xlsx\XlsxDefaultRendition :: set_headers($this->php_excel, $headers);

        $row = 2;

        foreach ($enrollments as $key => $enrollment)
        {
            $column = 0;

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_year()));
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_faculty()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_training()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_unified_option()));
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string($enrollment->get_unified_trajectory()));

            if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    ExcelExport :: transcode_string(Translation :: get($enrollment->get_contract_type_string())));
            }

            if ($enrollment->is_special_result())
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    ExcelExport :: transcode_string(Translation :: get($enrollment->get_result_string())));
            }
            else
            {
                $column ++;
            }

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column ++,
                $row,
                ExcelExport :: transcode_string(
                    Translation :: get(
                        $enrollment->get_generation_student() == 1 ? 'GenerationStudent' : 'NoGenerationStudent')));

            $row ++;
        }

        $this->php_excel->getActiveSheet()->calculateColumnWidths();
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
