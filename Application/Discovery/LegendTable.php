<?php
namespace Ehb\Application\Discovery;

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

    public function __construct($tableData, $defaultOrderColumn = 1, $defaultPerPage = 20, $tableName = 'tablename',
        $defaultOrderDirection = SORT_ASC)
    {
        parent :: __construct($tableData, $defaultOrderColumn, $defaultPerPage, $tableName, $defaultOrderDirection);

        $this->setColumnHeader(0, '', false);
        $this->setColumnHeader(1, Translation :: get('Type'), false);
        $this->setColumnHeader(2, Translation :: get('Legend'), false);
        $this->getHeader()->setColAttributes(0, 'class="action"');
    }

    /**
     * Returns the instance of this class.
     *
     * @return LegendTable
     */
    public static function getInstance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }

        return self :: $instance;
    }

    public function getData()
    {
        $tableData = array();

        foreach ($this->getData() as $category)
        {
            foreach ($category as $key => $row)
            {
                $tableData[$key] = $row;
            }
        }

        return $tableData;
    }

    /**
     *
     * @param string $symbol
     * @param string $description
     */
    public function addSymbol($symbol, $description = null, $category = null)
    {
        $key = md5($symbol);

        $data = $this->getData();
        if (! key_exists($category, $data) || ! key_exists($key, $data[$category]))
        {
            $data[$category][$key] = array($symbol, $category, $description);
            $this->setTableData($data);
        }
    }
}
