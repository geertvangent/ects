<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Ehb\Application\Discovery
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LegendTable extends SortableTable
{

    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    /**
     *
     * @param string[] $tableData
     */
    public function __construct($tableData)
    {
        $tableColumns = array();
        $tableColumns[] = new StaticTableColumn('');
        $tableColumns[] = new StaticTableColumn(Translation::get('Type'));
        $tableColumns[] = new StaticTableColumn(Translation::get('Legend'));
        
        parent::__construct($tableData, $tableColumns, array(), null, null, null, 'legend-table', false, false, false);
    }

    /**
     * Returns the instance of this class.
     * 
     * @return LegendTable
     */
    public static function getInstance()
    {
        if (! isset(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     *
     * @param string $symbol
     * @param string $description
     */
    public function addSymbol($symbol, $description = null, $category = null)
    {
        $key = md5($symbol);
        
        $data = $this->getTableData();
        if (! key_exists($category, $data) || ! key_exists($key, $data[$category]))
        {
            $data[$category][$key] = array($symbol, $category, $description);
            $this->setTableData($data);
        }
    }
}
