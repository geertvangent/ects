<?php
namespace Ehb\Application\Discovery\Module\Exemption;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;

abstract class Module extends \Ehb\Application\Discovery\Module
{
    const PARAM_USER_ID = 'user_id';

    /**
     *
     * @var multitype:\application\discovery\module\exemption\Exemption
     */
    private $exemptions;

    public function __construct(Application $application, Instance $module_instance)
    {
        parent::__construct($application, $module_instance);
    }

    public function get_module_parameters()
    {
        $parameter = self::module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    public static function module_parameters()
    {
        $param_user = Request::get(self::PARAM_USER_ID);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\exemption\TeachingAssignment
     */
    public function get_exemptions()
    {
        if (! isset($this->exemptions))
        {
            $this->exemptions = DataManager::getInstance($this->get_module_instance())->retrieve_exemptions(
                $this->get_module_parameters());
        }
        return $this->exemptions;
    }

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_exemptions($parameters);
    }

    public function get_type()
    {
        return Instance::TYPE_USER;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem::get_directory_content(
            Path::getInstance()->namespaceToFullPath(__NAMESPACE__) . 'implementation/', 
            Filesystem::LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\Implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
