<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\DataClassCountParameters;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\GalleryObjectTableDataProvider;

class GalleryBrowserTableDataProvider extends GalleryObjectTableDataProvider
{

    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return \user\DataManager :: retrieves(\user\User :: class_name(), $parameters);
    }

    function get_object_count()
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return \user\DataManager :: count(\user\User :: class_name(), $parameters);
    }
}
