<?php
namespace application\ehb_sync\cas\storage;

use libraries\Mdb2Database;

class Mdb2DataManager extends Mdb2Database implements DataManagerInterface
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        $this->set_connection(Mdb2Connection :: get_instance()->get_connection());
        $this->get_connection()->setCharset('utf8');
        $this->set_prefix('');
    }
}
