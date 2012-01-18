<?php
namespace application\discovery\module\exemption;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\exemption\Exemption
     */
    function retrieve_exemptions($person_id);
}
?>