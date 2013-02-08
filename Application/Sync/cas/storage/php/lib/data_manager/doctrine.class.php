<?php
namespace application\ehb_sync\cas\storage;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    function initialize()
    {
        $this->set_connection(DoctrineConnection :: get_instance()->get_connection());
        $this->get_connection()->setCharset('utf8');
        $this->set_prefix('');
    }
}
