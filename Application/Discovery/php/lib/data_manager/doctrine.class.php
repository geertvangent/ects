<?php
namespace application\discovery;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_');
    }
}
