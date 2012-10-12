<?php
namespace application\discovery\module\training_results;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param Parameters $training_results_parameters
     * @return multitype:\application\discovery\module\training_results\CourseResults
     */
    function retrieve_training_results($training_results_parameters);
}
?>