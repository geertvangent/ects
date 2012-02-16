<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use user\UserDataManager;

use common\libraries\GalleryObjectTableDataProvider;

class GalleryBrowserTableDataProvider extends GalleryObjectTableDataProvider
{

    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return UserDataManager :: get_instance()->retrieve_users($this->get_condition(), $offset, $count, $order_property);
    }

    function get_object_count()
    {
        return  UserDataManager :: get_instance()->count_users($this->get_condition());
    }
}
?>