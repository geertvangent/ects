<?php
namespace application\discovery\module\employment;

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
     * @return \application\discovery\module\employments\Employment boolean
     */
    function retrieve_employments($id);
}
