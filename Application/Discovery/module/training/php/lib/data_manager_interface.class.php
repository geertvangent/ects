<?php
namespace application\discovery\module\training;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    public function retrieve_trainings($year);
}
