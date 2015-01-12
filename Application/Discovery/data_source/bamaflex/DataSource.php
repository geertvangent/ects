<?php
namespace Chamilo\Application\Discovery\DataSource\Bamaflex;

class DataSource extends \Chamilo\Application\Discovery\DataSource
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $connection = Connection :: get_instance($data_source)->get_connection();

        if (Connection :: get_instance($data_source)->get_data_source_instance()->get_setting('driver') == 'mssql')
        {
            // Necessary to retrieve complete photos and other large datasets
            // from the database
            $connection->prepare('SET TEXTSIZE 2000000')->execute();
        }
        else
        {
            $connection->setCharset('utf8');
        }

        $this->set_connection($connection);
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
