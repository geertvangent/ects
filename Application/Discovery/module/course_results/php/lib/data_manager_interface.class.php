<?php
namespace application\discovery\module\course_results;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param $id
     * @return multitype:\application\discovery\module\course_results\CourseResults
     */
    function retrieve_course_results($programme_id);

    /**
     * @param string $user_id
     * @return multitype:\application\discovery\module\course_results\MarkMoment
     */
    function retrieve_mark_moments($user_id);
}
?>