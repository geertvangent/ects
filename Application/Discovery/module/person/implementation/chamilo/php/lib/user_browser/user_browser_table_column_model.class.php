<?php
namespace application\discovery\module\person\implementation\chamilo;
use application\discovery\module\person\Person;

use user\User;
use user\DefaultUserTableColumnModel;

use common\libraries\ObjectTableColumn;
use common\libraries\StaticTableColumn;
use common\libraries\Path;

class UserBrowserTableColumnModel extends DefaultUserTableColumnModel
{
    /**
     * The tables modification column
     */
    private static $modification_column;

    /**
     * Constructor
     */
    function __construct()
    {
        parent :: __construct();
        $this->add_column(new ObjectTableColumn(Person :: PROPERTY_EMAIL));
        $this->set_default_order_column(1);
        $this->add_column(self :: get_modification_column());
    }

    /**
     * Gets the modification column
     * @return ContentObjectTableColumn
     */
    static function get_modification_column()
    {
        if (! isset(self :: $modification_column))
        {
            self :: $modification_column = new StaticTableColumn('');
        }
        return self :: $modification_column;
    }
}
?>