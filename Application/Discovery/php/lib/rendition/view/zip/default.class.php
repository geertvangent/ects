<?php
namespace application\discovery;

use libraries\platform\Translation;
use libraries\platform\Session;
use libraries\file\Path;
use libraries\file\Filecompression;
use libraries\file\Filesystem;

class ZipDefaultRendition extends ZipRendition
{

    public function render()
    {
        return null;
    }

    /**
     *
     * @param string $temporary_directory
     */
    public static function save($temporary_directory, $file_name)
    {
        $zip = Filecompression :: factory();
        $zip_path = $zip->create_archive($temporary_directory);
        Filesystem :: remove($temporary_directory);
        
        $user_id = Session :: get_user_id();
        $path = Path :: get_temp_path(__NAMESPACE__) . $user_id . '/export_photos/' .
             Filesystem :: create_safe_name($file_name) . '.zip';
        
        if (Filesystem :: move_file($zip_path, $path))
        {
            return $path;
        }
        else
        {
            throw new \Exception(Translation :: get('FileMoveFailed'));
        }
    }
}
