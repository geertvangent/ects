<?php
namespace Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex;

class Parameters extends \Ehb\Application\Discovery\Module\Career\Parameters
{

    public function __construct($user_id, $contract_id)
    {
        parent :: __construct($user_id);
        $this->set_contract_id($contract_id);
    }

    public function get_contract_id()
    {
        return $this->get_parameter(Module :: PARAM_CONTRACT_ID);
    }

    public function set_contract_id($contract_id)
    {
        $this->set_parameter(Module :: PARAM_CONTRACT_ID, $contract_id);
    }
}
