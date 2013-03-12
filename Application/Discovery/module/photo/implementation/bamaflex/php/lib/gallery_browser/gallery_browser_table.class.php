<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\GalleryObjectTable;

class GalleryBrowserTable extends GalleryObjectTable
{
    const DEFAULT_NAME = 'gallery_browser_table';

    /**
     * Constructor
     *
     * @see ContentObjectTable::ContentObjectTable()
     */
    public function __construct($browser, $parameters, $condition)
    {
        $property_model = new GalleryBrowserTablePropertyModel();
        $cell_renderer = new GalleryBrowserTableCellRenderer($browser);
        $data_provider = new GalleryBrowserTableDataProvider($browser, $condition);

        parent :: __construct($data_provider, self :: DEFAULT_NAME, $cell_renderer, $property_model);

        $this->set_default_row_count(2);
        $this->set_default_column_count(4);
        $this->set_additional_parameters($parameters);
    }
}
