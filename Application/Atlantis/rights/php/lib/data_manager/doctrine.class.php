<?php
namespace application\atlantis\rights;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('atlantis_rights_');
    }
}
