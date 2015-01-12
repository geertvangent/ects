<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\storage\DataClassCountParameters;
use libraries\storage\DataClassRetrievesParameters;
use libraries\format\GalleryTableDataProvider;

class GalleryBrowserTableDataProvider extends GalleryTableDataProvider
{
    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return \core\user\DataManager :: retrieves(\core\user\User :: class_name(), $parameters);
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return \core\user\DataManager :: count(\core\user\User :: class_name(), $parameters);
    }
}
