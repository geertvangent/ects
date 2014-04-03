<?php
namespace application\atlantis\rights;

use common\libraries\Mdb2Database;

class Mdb2DataManager extends Mdb2Database implements DataManagerInterface
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('atlantis_');
    }
}
