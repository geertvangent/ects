<?php
namespace application\atlantis\role\entity;

use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('atlantis_');
    }
}
