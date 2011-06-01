<?php
namespace application\discovery;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
use common\libraries\Mdb2Database;

class Mdb2DiscoveryDataManager extends Mdb2Database implements DiscoveryDataManagerInterface
{

    function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_');
    }

    // Publication attributes
    function content_object_is_published($object_id)
    {
        return false;
    }

    function any_content_object_is_published($object_ids)
    {
        return false;
    }

    function get_content_object_publication_attributes($object_id, $type = null, $offset = null, $count = null, $order_properties = null)
    {
        return array();
    }

    function get_content_object_publication_attribute($publication_id)
    {
        return array();
    }

    function count_publication_attributes($user = null, $object_id = null, $condition = null)
    {
        return 0;
    }

    function delete_content_object_publications($object_id)
    {
        return true;
    }

    function update_content_object_publication_id($publication_attr)
    {
        return false;
    }
}
?>