<?php
namespace application\discovery\module\elo\implementation\chamilo;

use libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_elo_');
    }
}
