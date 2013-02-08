<?php
namespace application\discovery;

use common\libraries\Translation;

use common\libraries\Session;
use common\libraries\Path;
use common\libraries\Filecompression;
use common\libraries\Filesystem;

class ZipDefaultRendition extends ZipRendition
{

    function render()
    {
        return null;
    }

    /**
     *
     * @param string $temporary_directory
     */
    static function save($temporary_directory, $file_name)
    {
        $zip = Filecompression :: factory();
        $zip_path = $zip->create_archive($temporary_directory);
        Filesystem :: remove($temporary_directory);

        $user_id = Session :: get_user_id();
        $path = Path :: get_temp_path(__NAMESPACE__) . $user_id . '/export_photos/' . Filesystem :: create_safe_name($file_name) . '.zip';

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
