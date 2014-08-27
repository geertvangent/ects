<?php
namespace application\discovery\data_source;

use libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_data_source_');
    }
}
