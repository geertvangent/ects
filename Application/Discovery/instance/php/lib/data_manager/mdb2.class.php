<?php
namespace application\discovery\instance;

use libraries\Mdb2Database;

class Mdb2DataManager extends Mdb2Database
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_module_');
    }
}
