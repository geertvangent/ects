<?php
namespace application\ehb_sync\bamaflex;

use libraries\storage\DoctrineDatabase;

class BamaflexDatabase extends DoctrineDatabase
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        $this->set_connection(BamaflexConnection :: get_instance()->get_connection());
        $this->get_connection()->query('SET TEXTSIZE 2000000');
        $this->set_prefix('');
    }
}
