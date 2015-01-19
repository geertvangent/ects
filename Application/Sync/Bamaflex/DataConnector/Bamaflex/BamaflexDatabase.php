<?php
namespace Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex;

use Chamilo\Libraries\Storage\DataManager\Doctrine\Database;

class BamaflexDatabase extends Database
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
