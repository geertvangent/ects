<?php
namespace application\discovery\module\person\implementation\chamilo;

use libraries\utilities\Utilities;
use libraries\format\Table;

class GroupRelUserBrowserTable extends Table
{

    /**
     * Constructor
     *
     * @see ContentObjectTable::ContentObjectTable()
     */
    public function __construct($browser, $parameters, $condition)
    {
        $model = new GroupRelUserBrowserTableColumnModel();
        $renderer = new GroupRelUserBrowserTableCellRenderer($browser);
        $data_provider = new GroupRelUserBrowserTableDataProvider($browser, $condition);
        parent :: __construct(
            $data_provider,
            Utilities :: get_classname_from_namespace(__CLASS__, true),
            $model,
            $renderer);
        $this->set_additional_parameters($parameters);
    }

    /**
     * A typical ObjectTable would get the database-id of the object as a unique identifier. GroupRelUser has no such
     * field since it's a relation, so we need to overwrite this function here.
     */
    public function get_objects($offset, $count, $order_column)
    {
        $grouprelusers = $this->get_data_provider()->get_objects(
            $offset,
            $count,
            $this->get_column_model()->get_order_column($order_column - ($this->has_form_actions() ? 1 : 0)));
        $table_data = array();
        $column_count = $this->get_column_model()->get_column_count();
        while ($groupreluser = $grouprelusers->next_result())
        {
            $row = array();
            if ($this->has_form_actions())
            {
                $row[] = $groupreluser->get_group_id() . '|' . $groupreluser->get_user_id();
            }
            for ($i = 0; $i < $column_count; $i ++)
            {
                $row[] = $this->get_cell_renderer()->render_cell(
                    $this->get_column_model()->get_column($i),
                    $groupreluser);
            }
            $table_data[] = $row;
        }
        return $table_data;
    }
}
