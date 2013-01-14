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
                $this->get_module_instance()->get_id(), $this->get_enrollment_parameters()))
        {
            Display :: not_allowed();
        }

        $this->php_excel = new PHPExcel();
        $this->php_excel->removeSheetByIndex(0);

        $file_name = Translation :: get('TypeName', null, $this->get_module_instance()->get_type()) . date(
                '_Y-m-d_H-i-s') . '.xlsx';

        if (count($this->get_enrollments()) > 0)
        {
            $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types(
                    $this->get_enrollment_parameters());

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

        $this->php_excel->setActiveSheetIndex(0);

        $file = Path :: get(SYS_ARCHIVE_PATH) . Filesystem :: create_unique_name(Path :: get(SYS_ARCHIVE_PATH),
                $file_name);

        $this->php_excel_writer = PHPExcel_IOFactory :: createWriter($this->php_excel, 'Excel2007');
        $this->php_excel_writer->save($file);

        $this->php_excel->disconnectWorksheets();
        unset($this->php_excel);

        return $file;
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

        $row = 1;
        $column = 0;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('Year')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('Faculty')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('Training')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('Option')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('Trajectory')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                    StringUtilities :: transcode_string(Translation :: get('Contract')));
            $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                    \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
            $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                    'FFFFFF');
            $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
            // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
            $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                    \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
            $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
            $column ++;
        }

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                '');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth(3);
        $column ++;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('Result')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column, $row,
                StringUtilities :: transcode_string(Translation :: get('GenerationStudent')));
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(
                \PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB('c70d2f');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB(
                'FFFFFF');
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setName('Cambria');
        // $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $this->php_excel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $this->php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        $column ++;

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
                        StringUtilities :: transcode_string($enrollment->get_contract_type_string()));
            }

            if ($enrollment->is_special_result())
            {
                $result_image = new \PHPExcel_Worksheet_Drawing();
                $result_image->setName(Translation :: get($enrollment->get_result_string()));
                $result_image->setDescription(Translation :: get($enrollment->get_result_string()));
                $result_image->setPath(
                        Theme :: get_image_system_path(__NAMESPACE__) . 'result_type/' . $enrollment->get_result() . '.png');
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
                        StringUtilities :: transcode_string($enrollment->get_result_string()));
            }
            else
            {
                $column ++;
            }

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string(Translation :: get($enrollment->get_generation_student() == 1 ? 'Yes' : 'No', null,
                                Utilities :: COMMON_LIBRARIES)));

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