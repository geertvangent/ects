<?php
namespace Chamilo\Application\EhbSync\Bamaflex\DataConnector\Bamaflex;

use Chamilo\Libraries\Storage\ArrayResultSet;

class BamaflexResultSet extends ArrayResultSet
{

    public function __construct($statement)
    {
        $records = array();

        if (! $statement instanceof \Chamilo\PDOException)
        {
            while ($record = $statement->fetch(\Chamilo\PDO :: FETCH_ASSOC))
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
