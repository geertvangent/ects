<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

use common\libraries\Translation;
use common\libraries\StringUtilities;
use common\libraries\Display;
use common\libraries\Path;
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

        $this->process_course_results();

        return \application\discovery\XlsxDefaultRendition :: save($this->php_excel, $this->get_module());
    }

    function process_course_results()
    {
        $this->php_excel->createSheet(0);
        $this->php_excel->setActiveSheetIndex(0);
        $this->php_excel->getActiveSheet()->setTitle(Translation :: get('TypeName'));

        $headers = array();
        $headers[] = Translation :: get('LastName');
        $headers[] = Translation :: get('FirstName');
        $headers[] = Translation :: get('TrajectoryType');
        $headers[] = Translation :: get('FirstMark');
        $headers[] = Translation :: get('FirstMarkStatus');
        $headers[] = Translation :: get('SecondMark');
        $headers[] = Translation :: get('SecondMarkStatus');

        $this->php_excel->getActiveSheet()->getStyle(
                'A:' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
                        \PHPExcel_Style_Alignment :: HORIZONTAL_LEFT);

        \application\discovery\XlsxDefaultRendition :: set_headers($this->php_excel, $headers);

        $row = 2;

        foreach ($this->get_course_results() as $course_result)
        {
            $column = 0;
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($course_result->get_person_last_name()));
            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string($course_result->get_person_first_name()));

            $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                    StringUtilities :: transcode_string(
                            Translation :: get($course_result->get_trajectory_type_string(), null,
                                    'application\discovery\module\enrollment\implementation\bamaflex')));

            foreach ($this->get_mark_moments() as $mark_moment)
            {
                $mark = $course_result->get_mark_by_moment_id($mark_moment->get_id());

                if ($mark->get_result())
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                            StringUtilities :: transcode_string($mark->get_visual_result()));
                }
                else
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                            StringUtilities :: transcode_string($mark->get_sub_status()));
                }

                if ($mark->get_status())
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow($column ++, $row,
                            StringUtilities :: transcode_string(
                                    Translation :: get($mark->get_status_string(), null,
                                            'application\discovery\module\career\implementation\bamaflex')));
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
    function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_XLSX;
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