<?php
namespace application\discovery;

use common\libraries\Utilities;

use common\libraries\Translation;

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
        if ($this->get_total_number_of_items() == 0)
        {
            $cols = $this->getHeader()->getColCount();
            $this->setCellAttributes(0, 0, 'style="font-style:italic;text-align:center;" colspan="' . $cols . '"');
            $this->setCellContents(0, 0, Translation :: get('NoSearchResults', null, Utilities :: COMMON_LIBRARIES));
        }
        
        return $this->get_table_html();
    }
}
?>