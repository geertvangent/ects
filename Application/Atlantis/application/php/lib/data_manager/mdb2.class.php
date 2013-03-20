<?php
namespace application\atlantis\application;

use common\libraries\Mdb2Database;

class Mdb2DataManager extends Mdb2Database
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('atlantis_');
    }
}
