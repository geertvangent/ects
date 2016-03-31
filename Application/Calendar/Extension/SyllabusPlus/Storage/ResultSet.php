<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage;

use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;

class ResultSet extends ArrayResultSet
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

            if (! mb_check_encoding($field, 'UTF-8'))
            {
                if (is_string($field) && ! is_numeric($field))
                {
                    $field = iconv('Windows-1252', 'UTF-8', $field);
                }
            }
        }

        return $record;
    }
}
