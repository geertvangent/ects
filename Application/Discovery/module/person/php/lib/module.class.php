<?php
namespace application\discovery\module\person;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\WebApplication;
use common\libraries\Translation;
use common\libraries\Application;
use application\discovery\SortableTable;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{

    /**
     *
     * @var multitype:\application\discovery\module\person\Person
     */
    private $persons;

    private $cache_persons = array();

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        
        // $this->persons = DataManager :: get_instance($module_instance)->retrieve_persons();
    }

    /**
     *
     * @return multitype:\application\discovery\module\person\Faculty
     */
    function get_persons()
    {
        return $this->persons;
    }

    function get_persons_data()
    {
        return $this->get_persons();
    }

    function has_persons()
    {
        return count($this->get_persons_data()) > 0;
    }

    function get_persons_table()
    {
        $persons = $this->get_persons_data();
        
        $data = array();
        
        foreach ($persons as $key => $person)
        {
            $row = array();
            $row[] = $person->get_official_code();
            $row[] = $person->get_last_name() . ' ' . $person->get_first_name();
            $row[] = $person->get_email();
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('OfficialCode'), false, 'class="code"');
        $table->set_header(1, Translation :: get('Name'), false);
        $table->set_header(2, Translation :: get('Email'), false);
        
        return $table;
    }
    
    /*
     * (non-PHPdoc) @see application\discovery\module\person\Module::render()
     */
    function render()
    {
        $html = array();
        
        $html[] = $this->get_persons_table()->toHTML();
        
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_INFORMATION;
    }

    static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
                Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
?>