<?php
namespace application\discovery\instance;

use libraries\ObjectTableColumnModel;
use libraries\ObjectTableColumn;

class DefaultInstanceTableColumnModel extends ObjectTableColumnModel
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent :: __construct(self :: get_default_columns(), 1);
    }

    /**
     * Gets the default columns for this model
     * 
     * @return ContentObjectTableColumn[]
     */
    private static function get_default_columns()
    {
        $columns = array();
        $columns[] = new ObjectTableColumn(Instance :: PROPERTY_TYPE);
        $columns[] = new ObjectTableColumn(Instance :: PROPERTY_TITLE);
        $columns[] = new ObjectTableColumn(Instance :: PROPERTY_DESCRIPTION);
        $columns[] = new ObjectTableColumn(Instance :: PROPERTY_DISPLAY_ORDER);
        return $columns;
    }
}
