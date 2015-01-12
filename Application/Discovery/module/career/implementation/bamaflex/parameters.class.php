<?php
namespace application\discovery\module\career\implementation\bamaflex;

class Parameters extends \application\discovery\module\career\Parameters
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
