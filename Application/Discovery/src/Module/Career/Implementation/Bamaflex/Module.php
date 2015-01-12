<?php
namespace Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex;

use Chamilo\Libraries\Platform\Request;

class Module extends \Chamilo\Application\Discovery\Module\Career\Module
{
    const PARAM_CONTRACT_ID = 'contract_id';

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
        $param_contract_id = Request :: get(self :: PARAM_CONTRACT_ID);
        $parameter = new Parameters();
        
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        
        if (! is_null($param_contract_id))
        {
            $parameter->set_contract_id($param_contract_id);
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
}
