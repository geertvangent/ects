<?php
namespace application\ehb_sync\bamaflex;

use libraries\ArrayResultSet;

class BamaflexResultSet extends ArrayResultSet
{

    public function __construct($statement)
    {
        $records = array();

        if (! $statement instanceof \PDOException)
        {
            while ($record = $statement->fetch(\PDO :: FETCH_ASSOC))
            {
                $records[] = $this->process_record($record);
            }
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
