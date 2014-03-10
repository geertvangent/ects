<?php
namespace application\discovery;

use common\libraries\Mdb2Database;

class Mdb2DataManager extends Mdb2Database implements DataManagerInterface
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_');
    }
}
