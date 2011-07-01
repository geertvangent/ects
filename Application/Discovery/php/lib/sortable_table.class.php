<?php
namespace application\discovery;

use common\libraries\SortableTableFromArray;

class SortableTable extends SortableTableFromArray
{

    /**
     * Get table data to show on current page
     * @see SortableTable#get_table_data
     */
    function get_table_data()
    {
        return $this->get_data();
    }

    function as_html($empty_table = false)
    {
        return $this->get_table_html();
    }
}
?>