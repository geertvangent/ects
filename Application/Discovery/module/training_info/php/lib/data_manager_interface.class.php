<?php
namespace application\discovery\module\training_info;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    function retrieve_training($training_parameters);
}
?>