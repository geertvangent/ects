<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_elo_');
    }
}
