<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization;

use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex\BamaflexDataConnector;
use Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex\BamaflexResultSet;

abstract class Synchronization
{

    /**
     *
     * @var SqlDataManager
     */
    private $data_manager;

    public $course_types = array('2012-13' => 1, '2013-14' => 5, '2014-15' => 6, '2015-16' => 7);

    public function __construct()
    {
        $this->data_manager = BamaflexDataConnector :: get_instance();
    }

    /**
     *
     * @return SqlDataManager
     */
    public function get_data_manager()
    {
        return $this->data_manager;
    }

    /**
     *
     * @return string
     */
    public function get_academic_year()
    {
        return PlatformSetting :: get('academic_year', 'Ehb\Application\Sync');
    }

    public function get_academic_year_end()
    {
        $year_parts = explode('-', $this->get_academic_year());

        return '20' . $year_parts[1] . '-09-30 23:59:59.999';
    }

    /**
     *
     * @param $type string
     * @return Synchronization
     */
    public static function factory($type)
    {
        $class = __NAMESPACE__ . '\\' . StringUtilities :: getInstance()->createString($type)->upperCamelize() .
             'Synchronization';
        if (class_exists($class))
        {
            return new $class();
        }
    }

    abstract public function run();

    /**
     *
     * @param $string string
     * @return string
     */
    public function convert_to_utf8($string)
    {
        return trim(iconv('cp1252', 'UTF-8', $string));
    }

    /**
     *
     * @param $query string
     * @return \libraries\storage\RecordResultSet
     */
    public function get_result($query)
    {
        $statement = $this->get_data_manager()->get_connection()->query($query);
        return new BamaflexResultSet($statement);
    }

    /**
     *
     * @param $type string
     * @param $message string
     */
    public static function log($type, $message)
    {
        echo '[' . str_pad(strtoupper($type), 18, ' ', STR_PAD_LEFT) . '] ' . $message . "\n";
    }
}
