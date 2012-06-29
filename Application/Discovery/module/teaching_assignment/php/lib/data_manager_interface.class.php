<?php
namespace application\discovery\module\teaching_assignment;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    function retrieve_teaching_assignments($person_id);
}
?>