<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\Filesystem;

/**
 * @package ehb.sync;
 */







use user\User;

class DocUserSynchronization extends UserSynchronization
{

    function run()
    {
        $query = 'SELECT [Content] FROM [WSS_Content].[dbo].[AllDocStreams] WHERE id = "B54A3613-E840-4FCE-B9EB-BB04ECD38F87"';

        $user_result_set = $this->get_result($query);

        while ($user = $user_result_set->next_result(false))
        {
            header('Expires: Wed, 01 Jan 1990 00:00:00 GMT');
             header('Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
//            header('Content-type: application/msword');
//             header('Content-type: application/pdf');
//             header('Content-length: ' . $this->get_filesize());
//             header('Content-Description: test.pdf');
//             header('Content-Disposition: inline; filename= test.pdf');

//             Filesystem::write_to_file('G:\test.pdf', $user['content']);

            echo $user['content'];
            exit;
        }
    }
}
