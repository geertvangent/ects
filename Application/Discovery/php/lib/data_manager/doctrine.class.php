<?php
namespace application\discovery;

use libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_');
    }
}
