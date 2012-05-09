<?php
namespace application\atlantis\context;

use common\libraries\Mdb2Database;

class Mdb2DataManager extends Mdb2Database
{

    function initialize()
    {
        parent :: initialize();
        $this->set_prefix('atlantis_');
    }
}
?>