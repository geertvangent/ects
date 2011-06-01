<?php
namespace application\discovery;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DiscoveryDataManagerInterface
{

    function initialize();

    function create_storage_unit($name, $properties, $indexes);
}
?>