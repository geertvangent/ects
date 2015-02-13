<?php
namespace Ehb\Application\Discovery\DataSource\Bamaflex;

class DataSource extends \Ehb\Application\Discovery\DataSource
{

    public function __construct(\Ehb\Application\Discovery\Instance\DataClass\Instance $module_instance)
    {
        parent :: __construct($module_instance);
        $data_source_connection = Connection :: get_instance($this->get_module_instance()->get_setting('data_source'));

        $this->connection = $data_source_connection->get_connection();

        if ($data_source_connection->get_data_source_instance()->get_setting('driver') == 'mssql')
        {
            // Necessary to retrieve complete photos and other large datasets
            // from the database
            $this->connection->prepare('SET TEXTSIZE 2000000')->execute();
        }
        else
        {
            $this->connection->setCharset('utf8');
        }
    }

    /**
     *
     * @param $string string
     * @return string
     */
    public function convert_to_utf8($string)
    {
        if (Connection :: get_instance($this->get_module_instance()->get_setting('data_source'))->get_data_source_instance()->get_setting(
            'driver') == 'mssql')
        {
            return iconv('Windows-1252', 'UTF-8', $string);
        }
        else
        {
            return iconv(mb_detect_encoding($string), 'UTF-8', $string);
        }
    }
}
