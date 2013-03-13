<?php
namespace application\discovery;

use common\libraries\Filesystem;
use common\libraries\Path;
use common\libraries\Translation;
use common\libraries\StringUtilities;

class XlsxDefaultRendition extends XlsxRendition
{

    public function render()
    {
        return null;
    }

    /**
     *
     * @param \PHPExcel $php_excel
     * @param multitype:string $headers
     */
    public static function set_headers(\PHPExcel $php_excel, $headers, $row = 1)
    {
        $column = 0;
        
        foreach ($headers as $header)
        {
            $php_excel->getActiveSheet()->setCellValueByColumnAndRow(
                $column, 
                $row, 
                StringUtilities :: transcode_string($header));
            $php_excel->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
            $column ++;
        }
        
        // Set styles
        $range = 'A' . $row . ':' . \PHPExcel_Cell :: stringFromColumnIndex($column - 1) . $row;
        $php_excel->getActiveSheet()->getStyle($range)->getFill()->setFillType(\PHPExcel_Style_Fill :: FILL_SOLID)->getStartColor()->setRGB(
            'c70d2f');
        $php_excel->getActiveSheet()->getStyle($range)->getFont()->getColor()->setRGB('FFFFFF');
        $php_excel->getActiveSheet()->getStyle($range)->getFont()->setName('DejaVu Serif');
        $php_excel->getActiveSheet()->getStyle($range)->getFont()->setBold(true);
        $php_excel->getActiveSheet()->getStyle($range)->getFont()->setSize(12);
        $php_excel->getActiveSheet()->getStyle($range)->getAlignment()->setHorizontal(
            \PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
    }

    /**
     *
     * @param \PHPExcel $php_excel
     * @param string $type
     */
    public static function save(\PHPExcel $php_excel, $module, $file_name = null)
    {
        $php_excel->setActiveSheetIndex(0);
        
        if ($file_name)
        {
            $file_name = $file_name . '.xlsx';
        }
        else
        {
            if ($module->get_module_instance()->get_content_type() == \application\discovery\ModuleInstance :: TYPE_USER)
            {
                $user_id = $module->get_module_parameters()->get_user_id();
                $user = \user\DataManager :: retrieve_by_id(\user\User :: class_name(), (int) $user_id);
                
                $file_name = $user->get_fullname() . ' ' .
                     Translation :: get('TypeName', null, $module->get_module_instance()->get_type()) . '.xlsx';
            }
            else
            {
                $file_name = Translation :: get('TypeName', null, $module->get_module_instance()->get_type()) . '.xlsx';
            }
        }
        
        $path = Path :: get_temp_path($module->get_module_instance()->get_type()) . 'xlsx/';
        $file = $path . Filesystem :: create_unique_name($path, $file_name);
        
        if (Filesystem :: create_dir($path))
        {
            $php_excel_writer = \PHPExcel_IOFactory :: createWriter($php_excel, 'Excel2007');
            $php_excel_writer->save($file);
            
            $php_excel->disconnectWorksheets();
            unset($php_excel);
            
            return $file;
        }
        else
        {
            throw new \Exception(Translation :: get('PathNotCreated', array('PATH' => $path)));
        }
    }
}
