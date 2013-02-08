<?php
namespace application\discovery\module\student_year;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     *
     * @param Parameters $student_year_parameters
     * @return multitype:\application\discovery\module\student_year\StudentYear
     */
    function retrieve_student_years($student_year_parameters);
}
