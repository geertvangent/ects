<?php
namespace application\discovery\module\career;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\career\Career
     */
    function retrieve_root_courses($id);
}
?>