<?php
namespace application\discovery;

use common\libraries\DoctrineDatabase;

class DataSource extends DoctrineDatabase
{

    private $module_instance;

    /**
     * Constructor
     *
     * @param ModuleInstance $module_instance
     */
    public function __construct(ModuleInstance $module_instance)
    {
        $this->module_instance = $module_instance;
        $this->initialize();
    }

    public function get_module_instance()
    {
        return $this->module_instance;
    }

    public function set_module_instance(ModuleInstance $module_instance)
    {
        $this->module_instance = $module_instance;
    }
}
