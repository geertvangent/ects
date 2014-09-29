<?php
namespace application\discovery\instance;

use libraries\TableColumnModel;
use libraries\TableColumn;

class DefaultInstanceTableColumnModel extends TableColumnModel
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
        $columns[] = new TableColumn(Instance :: PROPERTY_TYPE);
        $columns[] = new TableColumn(Instance :: PROPERTY_TITLE);
        $columns[] = new TableColumn(Instance :: PROPERTY_DESCRIPTION);
        $columns[] = new TableColumn(Instance :: PROPERTY_DISPLAY_ORDER);
        return $columns;
    }
	/* (non-PHPdoc)
     * @see \libraries\TableColumnModel::initialize_columns()
     */
    public function initialize_columns()
    {
        // TODO Auto-generated method stub

    }

}
