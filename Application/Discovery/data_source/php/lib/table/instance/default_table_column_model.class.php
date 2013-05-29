<?php
namespace application\discovery\data_source;

use common\libraries\ObjectTableColumnModel;
use common\libraries\ObjectTableColumn;

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
        $columns[] = new ObjectTableColumn(Instance :: PROPERTY_NAME);
        $columns[] = new ObjectTableColumn(Instance :: PROPERTY_DESCRIPTION);
        return $columns;
    }
}
