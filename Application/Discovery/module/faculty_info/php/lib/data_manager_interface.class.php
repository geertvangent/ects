<?php
namespace application\discovery\module\faculty_info;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    function retrieve_faculty($faculty_parameters);
}
?>