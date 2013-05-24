<?php
namespace application\discovery\module\course_results;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     *
     * @param Parameters $course_results_parameters
     * @return multitype:\application\discovery\module\course_results\CourseResults
     */
    public function retrieve_course_results($course_results_parameters);

    /**
     *
     * @param Parameters $course_results_parameters
     * @return multitype:\application\discovery\module\course_results\MarkMoment
     */
    public function retrieve_mark_moments($course_results_parameters);
}
