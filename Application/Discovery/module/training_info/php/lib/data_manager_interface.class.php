<?php
namespace application\discovery\module\training_info;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\training_info\TrainingInfo
     */
    function retrieve_training($training_parameters);
}
?>