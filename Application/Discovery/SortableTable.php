<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Platform\Translation;

class SortableTable extends SortableTableFromArray
{

    public function toHtml($totalValue, $totalColumn)
    {
        $tableData = $this->getData($this->getFrom());
        
        foreach ($tableData as $index => & $row)
        {
            $rowId = $row[0];
            $row = $this->filterData($row);
            $currentRow = $this->addRow($row);
            $this->setRowAttributes($currentRow, array('id' => 'row_' . $rowId), true);
        }
        
        $this->altRowAttributes(0, array('class' => 'row_even'), array('class' => 'row_odd'), true);
        
        foreach ($this->getHeaderAttributes() as $column => & $attributes)
        {
            $this->setCellAttributes(0, $column, $attributes);
        }
        
        foreach ($this->getContentCellAttributes() as $column => & $attributes)
        {
            $this->setColAttributes($column, $attributes);
        }
        
        if ($totalValue && $totalColumn)
        {
            $dataRow = array();
            $dataRow[$totalColumn] = $totalValue;
            $dataRow[0] = Translation::get('Total');
            
            $this->addRow($dataRow);
            
            $this->setCellAttributes(
                ($this->countData()), 
                0, 
                'colspan="' . ($totalColumn) . '" style="font-weight:bold; text-transform:uppercase; text-align:right;"');
            $this->setCellAttributes(
                ($this->countData()), 
                $totalColumn, 
                'colspan="' . ($this->getColCount() - $totalColumn) .
                     '" style="font-weight:bold; text-transform:uppercase;"');
        }
        
        return \HTML_Table::toHtml();
    }
}
