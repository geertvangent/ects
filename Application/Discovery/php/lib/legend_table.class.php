<?php
namespace application\discovery;

use common\libraries\Translation;

class LegendTable extends SortableTable
{

    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    public function __construct($table_data, $default_column = 1, $default_items_per_page = 20, $tablename = 'tablename',
            $default_direction = SORT_ASC)
    {
        parent :: __construct($table_data, $default_column, $default_items_per_page, $tablename, $default_direction);

        $this->set_header(0, '', false);
        $this->set_header(1, Translation :: get('Type'), false);
        $this->set_header(2, Translation :: get('Legend'), false);
        $this->getHeader()->setColAttributes(0, 'class="action"');
    }

    /**
     * Get table data to show on current page
     *
     * @see SortableTable#get_table_data
     */
    public function get_table_data()
    {
        $table_data = array();

        foreach ($this->get_data() as $category)
        {
            foreach ($category as $key => $row)
            {
                $table_data[$key] = $row;
            }
        }

        return $table_data;
    }

    /**
     * Returns the instance of this class.
     *
     * @return LegendTable
     */
    public static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    /**
     *
     * @param string $symbol
     * @param string $description
     */
    public function add_symbol($symbol, $description = null, $category = null)
    {
        $key = md5($symbol);

        $data = $this->get_data();
        if (! key_exists($category, $data) || ! key_exists($key, $data[$category]))
        {
            $data[$category][$key] = array($symbol, $category, $description);
            $this->set_data($data);
        }
    }
}
