<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\DataClassCountParameters;
use libraries\DataClassRetrievesParameters;
use libraries\GalleryObjectTableDataProvider;

class GalleryBrowserTableDataProvider extends GalleryObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return \core\user\DataManager :: retrieves(\core\user\User :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return \core\user\DataManager :: count(\core\user\User :: class_name(), $parameters);
    }
}
