<?php
namespace application\ehb_sync\data;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('ehb_sync_data_');
    }
}
