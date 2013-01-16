<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\StringUtilities;
use common\libraries\Filesystem;
use common\libraries\Translation;
use common\libraries\Display;
use common\libraries\Path;
use application\discovery\module\enrollment\DataManager;
use PHPExcel;
use PHPExcel_IOFactory;

require_once Path :: get_plugin_path() . 'phpexcel/PHPExcel.php';
class XlsxDefaultRenditionImplementation extends RenditionImplementation
{

    /**
     *
     * @var \PHPExcel
     */
    private $php_excel;

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

        $this->php_excel = new PHPExcel();
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

        return \application\discovery\XlsxDefaultRendition :: save($this->php_excel,
                $this->get_module_instance()->get_type());
    }

    function process_enrollments($contract_type = Enrollment :: CONTRACT_TYPE_ALL)
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

        $headers[] = '-';
        $headers[] = Translation :: get('Result');
        $headers[] = Translation :: get('GenerationStudent');

        \application\discovery\XlsxDefaultRendition :: set_headers($this->php_excel, $headers);

        $row = 2;

        foreach ($enrollments as $key => $enrollment)
        {
            $column = 0;

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($enrollment->get_year()));
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($enrollment->get_faculty()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($enrollment->get_training()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($enrollment->get_unified_option()));
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($enrollment->get_unified_trajectory()));

            if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                        StringUtilities :: transcode_string(Translation :: get($enrollment->get_contract_type_string())));
            }

            if ($enrollment->is_special_result())
            {
                $result_image = new \PHPExcel_Worksheet_Drawing();
                $result_image->setName(Translation :: get($enrollment->get_result_string()));
                $result_image->setDescription(Translation :: get($enrollment->get_result_string()));
                $result_image->setPath(
                        Theme :: get_image_system_path() . 'result_type/' . $enrollment->get_result() . '.png');
                $result_image->setHeight(16);
                $result_image->setCoordinates(\PHPExcel_Cell :: stringFromColumnIndex($column ++) . $row);
                $result_image->setWorksheet($this->php_excel->getActiveSheet());
            }
            else
            {
                $column ++;
            }

            if ($enrollment->is_special_result())
            {
                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                        StringUtilities :: transcode_string(Translation :: get($enrollment->get_result_string())));
            }
            else
            {
                $column ++;
            }

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string(
                            Translation :: get(
                                    $enrollment->get_generation_student() == 1 ? 'GenerationStudent' : 'NoGenerationStudent')));

            $row ++;
        }

        $this->php_excel->getActiveSheet()->calculateColumnWidths();
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