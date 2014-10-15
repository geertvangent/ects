<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use application\discovery\module\group_user\DataManager;
use application\discovery\module\group\implementation\bamaflex\Group;
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

        if (count($this->get_group_user()) > 0)
        {
            $this->process_group_users();
        }

        return \application\discovery\XlsxDefaultRendition :: save(
            $this->php_excel,
            $this->get_module(),
            $this->get_file_name());
    }

    public function get_file_name()
    {
        $group = DataManager :: get_instance($this->get_module_instance())->retrieve_group(
            Module :: get_group_parameters());

        return $group->get_description() . ' ' .
             Translation :: get('TypeName', null, $this->get_module_instance()->get_type()) . ' ' . $group->get_year();
    }

    public function process_group_users()
    {
        $data = array();
        $data_struck = array();

        $cache = array();

        foreach ($this->get_group_user() as $group_user)
        {

            if ($group_user->get_struck() == 0)
            {
                $data[] = $this->get_row($group_user);
            }
            else
            {
                $data_struck[] = $this->get_row($group_user);
            }
            $cache[$group_user->get_struck()][] = $group_user->get_person_id();
        }

        $course_data = array();
        $course_data_struck = array();

        if ($this->get_module_parameters()->get_type() == Group :: TYPE_CLASS)
        {
            $parameters = $this->get_module_parameters();
            $parameters->set_type(Group :: TYPE_CLASS_COURSE);
            $class_course_users = $this->get_data_manager()->retrieve_group_users($parameters);

            foreach ($class_course_users as $course_user)
            {
                if (! in_array($course_user->get_person_id(), $cache[$course_user->get_struck()]))
                {
                    if ($course_user->get_struck() == 0)
                    {
                        $course_data[] = $this->get_row($course_user);
                    }
                    else
                    {
                        $course_data_struck[] = $this->get_row($course_user);
                    }
                }
            }
        }

        $headers = array();
        $headers[] = Translation :: get('PersonId');
        $headers[] = Translation :: get('FirstName');
        $headers[] = Translation :: get('LastName');
        $headers[] = Translation :: get('Type');

        if (count($data) > 0 || count($course_data) > 0)
        {
            $this->php_excel->createSheet(0);
            $this->php_excel->setActiveSheetIndex(0);
            $this->php_excel->getActiveSheet()->setTitle(Translation :: get('Enrolled'));

            $row = 1;

            $this->php_excel->getActiveSheet()->getStyle(
                'A:' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_LEFT);

            \application\discovery\XlsxDefaultRendition :: set_headers($this->php_excel, $headers, $row);
            $row ++;

            foreach ($data as $group_user)
            {
                $column = 0;

                foreach ($group_user as $group_user_info)
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++,
                        $row,
                        StringUtilities :: transcode_string($group_user_info));
                }

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string(Translation :: get('Enrollment')));

                $row ++;
            }

            if (count($course_data) > 0)
            {
                foreach ($course_data as $group_user)
                {
                    $column = 0;

                    foreach ($group_user as $group_user_info)
                    {
                        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                            $column ++,
                            $row,
                            StringUtilities :: transcode_string($group_user_info));
                    }

                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++,
                        $row,
                        StringUtilities :: transcode_string(Translation :: get('Course')));

                    $row ++;
                }
            }
        }

        if (count($data_struck) > 0 || count($course_data_struck) > 0)
        {
            $this->php_excel->createSheet(1);
            $this->php_excel->setActiveSheetIndex(1);
            $this->php_excel->getActiveSheet()->setTitle(Translation :: get('Struck'));

            $row = 1;

            $this->php_excel->getActiveSheet()->getStyle(
                'A:' . \PHPExcel_Cell :: stringFromColumnIndex(count($headers) - 1))->getAlignment()->setHorizontal(
                \PHPExcel_Style_Alignment :: HORIZONTAL_LEFT);

            \application\discovery\XlsxDefaultRendition :: set_headers($this->php_excel, $headers, $row);
            $row ++;

            foreach ($data_struck as $group_user)
            {
                $column = 0;

                foreach ($group_user as $group_user_info)
                {
                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++,
                        $row,
                        StringUtilities :: transcode_string($group_user_info));
                }

                $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                    $column ++,
                    $row,
                    StringUtilities :: transcode_string(Translation :: get('Enrollment')));

                $row ++;
            }

            if (count($course_data_struck) > 0)
            {
                foreach ($course_data_struck as $group_user)
                {
                    $column = 0;

                    foreach ($group_user as $group_user_info)
                    {
                        $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                            $column ++,
                            $row,
                            StringUtilities :: transcode_string($group_user_info));
                    }

                    $this->php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                        $column ++,
                        $row,
                        StringUtilities :: transcode_string(Translation :: get('Course')));

                    $row ++;
                }
            }
        }
    }

    public function get_row($group_user)
    {
        $row = array();
        $row[] = $group_user->get_person_id();
        $row[] = $group_user->get_last_name();
        $row[] = $group_user->get_first_name();
        return $row;
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
