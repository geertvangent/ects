<?php
namespace Chamilo\Application\Discovery\Module\Career;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Application\Discovery\Instance\DataClass\Instance;

class Module extends \Chamilo\Application\Discovery\Module
{
    const PARAM_USER_ID = 'user_id';

    /**
     *
     * @var multitype:\application\discovery\module\career\Course
     */
    private $courses;

    /**
     *
     * @var multitype:\application\discovery\module\career\MarkMoment
     */
    private $mark_moments;

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
        return $parameter;
    }

    public static function module_parameters()
    {
        $param_user = Request :: get(self :: PARAM_USER_ID);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\career\Course
     */
    public function get_courses()
    {
        if (! isset($this->courses))
        {
            $this->courses = $this->get_data_manager()->retrieve_courses($this->get_module_parameters());
        }
        return $this->courses;
    }

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_courses($parameters);
    }

    /**
     *
     * @return multitype:\application\discovery\module\career\MarkMoment
     */
    public function get_mark_moments()
    {
        if (! isset($this->mark_moments))
        {
            $this->mark_moments = $this->get_data_manager()->retrieve_mark_moments($this->get_module_parameters());
        }
        return $this->mark_moments;
    }

    public function get_type()
    {
        return Instance :: TYPE_USER;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            ClassnameUtilities :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'implementation/', 
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
