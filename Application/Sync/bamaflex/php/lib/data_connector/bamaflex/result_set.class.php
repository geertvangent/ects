<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\ArrayResultSet;

class BamaflexResultSet extends ArrayResultSet
{

    public function __construct($handle)
    {
        $records = array();
        if ($handle instanceof \MDB2_Error)
        {
            var_dump($handle);
        }
        while ($record = $handle->fetchRow(MDB2_FETCHMODE_ASSOC))
        {
            $records[] = $this->process_record($record);
        }

        parent :: __construct($records);
    }

    public function process_record($record)
    {
        foreach ($record as &$field)
        {
            if (is_resource($field))
            {
                $data = '';
                while (! feof($field))
                {
                    $data .= fread($field, 1024);
                }
                $field = $data;
            }
        }

        return $record;
    }
}
