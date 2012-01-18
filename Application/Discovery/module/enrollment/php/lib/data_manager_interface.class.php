<?php
namespace application\discovery\module\enrollment;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\enrollment\Enrollment
     */
    function retrieve_enrollments($id);
}
?>