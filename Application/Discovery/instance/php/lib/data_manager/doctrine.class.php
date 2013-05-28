<?php
namespace application\discovery\instance;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_module_');
    }
}
