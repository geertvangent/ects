<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\Mdb2Database;

class BamaflexDatabase extends Mdb2Database implements DataManagerInterface
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    function initialize()
    {
        $this->set_connection(BamaflexConnection :: get_instance()->get_connection());
        $this->get_connection()->setCharset('utf8');
        $this->set_prefix('');
    }
}
?>