<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\GalleryBrowser;

use Chamilo\Libraries\Format\Table\Extension\GalleryTable\GalleryTableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

class GalleryBrowserTableDataProvider extends GalleryTableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return \Chamilo\Core\User\Storage\DataManager :: retrieves(
            \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
            $parameters);
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return \Chamilo\Core\User\Storage\DataManager :: count(
            \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
            $parameters);
    }
}
