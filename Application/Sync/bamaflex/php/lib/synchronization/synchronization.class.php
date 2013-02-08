<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\PlatformSetting;

use common\libraries\Utilities;

abstract class Synchronization
{
    /**
     *
     * @var SqlDataManager
     */
    private $data_manager;

    function __construct()
    {
        $this->data_manager = BamaflexDataConnector :: get_instance();
    }

    /**
     *
     * @return SqlDataManager
     */
    function get_data_manager()
    {
        return $this->data_manager;
    }
    /**
     *
     * @return string
     */
    function get_academic_year()
    {
        return PlatformSetting::get('academic_year', __NAMESPACE__);
    }

    function get_academic_year_end()
    {
        $year_parts = explode('-', $this->get_academic_year());

        return '20'. $year_parts[1] .'-09-30 23:59:59.999';
    }

    /**
     *
     * @param $type string
     * @return Synchronization
     */
    static function factory($type)
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'Synchronization';
        if (class_exists($class))
        {
            return new $class();
        }
    }

    abstract function run();

    /**
     *
     * @param $string string
     * @return string
     */
    function convert_to_utf8($string)
    {
        return iconv('cp1252', 'UTF-8', $string);
    }

    /**
     *
     * @param $query string
     * @return \common\libraries\RecordResultSet
     */
    function get_result($query)
    {
        $statement = $this->get_data_manager()->get_connection()->prepare($query);

        return new BamaflexResultSet($statement->execute());
    }

    /**
     *
     * @param $type string
     * @param $message string
     */
    static function log($type, $message)
    {
        echo '[' . str_pad(strtoupper($type), 18, ' ', STR_PAD_LEFT) . '] ' . $message . "\n";
    }
}
