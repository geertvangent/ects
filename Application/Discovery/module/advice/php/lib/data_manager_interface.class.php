<?php
namespace application\discovery\module\advice;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\advice\Advice
     */
    function retrieve_advices($person_id);
}
?>