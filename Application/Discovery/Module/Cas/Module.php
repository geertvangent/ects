<?php
namespace Chamilo\Application\Discovery\Module\Cas;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Application\Discovery\Instance\DataClass\Instance;
use Chamilo\Application\Discovery\Module\Profile\DataManager;

abstract class Module extends \Chamilo\Application\Discovery\Module
{

    private $cas_statistics;

    private $applications;
    const PARAM_USER_ID = 'user_id';
    const PARAM_MODE = 'mode';

    public function __construct(Application $application, Instance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    public function get_module_parameters()
    {
        $parameter = self :: module_parameters();
        
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        
        if (! $parameter->get_mode())
        {
            $parameter->set_mode(Parameters :: MODE_USER);
        }
        elseif ($parameter->get_mode() == Parameters :: MODE_GENERAL)
        {
            $parameter->set_user_id(0);
        }
        
        return $parameter;
    }

    public static function module_parameters()
    {
        $param_user = Request :: get(self :: PARAM_USER_ID);
        $param_mode = Request :: get(self :: PARAM_MODE);
        
        $parameter = new Parameters();
        
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        
        if ($param_mode)
        {
            $parameter->set_mode($param_mode);
            
            if ($param_mode == Parameters :: MODE_GENERAL)
            {
                $parameter->set_user_id(0);
            }
        }
        
        return $parameter;
    }

    public function get_cas_statistics()
    {
        if (! isset($this->cas_statistics))
        {
            $path = Path :: get(SYS_FILE_PATH) . Path :: namespace_to_path(__NAMESPACE__) . '/cas_statistics/' .
                 md5(serialize($this->get_module_parameters()));
            
            if (! file_exists($path))
            {
                $this->cas_statistics = DataManager :: get_instance($this->get_module_instance())->retrieve_cas_statistics(
                    $this->get_module_parameters());
                Filesystem :: write_to_file($path, serialize($this->cas_statistics));
            }
            else
            {
                $this->cas_statistics = unserialize(file_get_contents($path));
            }
        }
        
        return $this->cas_statistics;
    }

    public function get_applications()
    {
        if (! isset($this->applications))
        {
            $this->applications = DataManager :: get_instance($this->get_module_instance())->retrieve_applications();
        }
        return $this->applications;
    }

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_cas_statistics($parameters);
    }

    public function get_type()
    {
        return Instance :: TYPE_USER;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', 
            Filesystem :: LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
