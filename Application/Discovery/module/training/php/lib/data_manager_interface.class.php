<?php
namespace application\discovery\module\training;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param int $id
     * @return multitype:\application\discovery\module\training\Training
     */
    function retrieve_trainings($training_parameters);
}
?>