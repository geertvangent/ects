<?php
namespace Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex;

use Chamilo\Libraries\Storage\DataManager\Doctrine\Database;

class BamaflexDatabase extends Database
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function __construct()
    {
        parent :: __construct(BamaflexConnection :: get_instance()->get_connection());
    }
}
