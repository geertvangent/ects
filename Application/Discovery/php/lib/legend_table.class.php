<?php
namespace application\discovery;

use common\libraries\Translation;

class LegendTable extends SortableTable
{
    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    function __construct($table_data, $default_column = 1, $default_items_per_page = 20, $tablename = 'tablename', $default_direction = SORT_ASC)
    {
        parent :: __construct($table_data, $default_column, $default_items_per_page, $tablename, $default_direction);

        $this->set_header(0, '', false);
        $this->set_header(1, Translation :: get('Legend'), false);
        $this->getHeader()->setColAttributes(0, 'class="action"');

    }

    /**
     * Returns the instance of this class.
     * @return LegendTable
     */
    static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    /**
     * @param string $symbol
     * @param string $description
     */
    function add_symbol($symbol, $description)
    {
        $key = md5($symbol);

        $data = $this->get_data();
        if (! key_exists($key, $data))
        {
            $data[$key] = array($symbol, $description);
            $this->set_data($data);
        }
    }
}
?>