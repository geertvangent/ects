<?php
namespace application\discovery\module\course;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     *
     * @param Parameters $courses_parameters
     * @return multitype:\application\discovery\module\course\Courses
     */
    public function retrieve_course($course_parameters);
}
