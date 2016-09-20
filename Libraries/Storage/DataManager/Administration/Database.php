<?php
namespace Ehb\Libraries\Storage\DataManager\Administration;

use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters;
use Ehb\Libraries\Storage\DataManager\Administration\ResultSet\RecordResultSet;

/**
 *
 * @package Ehb\Libraries\Storage\DataManager\Administration
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Database extends \Chamilo\Libraries\Storage\DataManager\Doctrine\Database
{

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function __construct()
    {
        parent::__construct(Connection::getInstance()->getConnection());
    }

    public function distinct($class, DataClassDistinctParameters $parameters)
    {
        $distinctElements = parent::distinct($class, $parameters);
        array_walk_recursive(
            $distinctElements,
            function (&$distinctElement, $key)
            {
                $distinctElement = trim(iconv('cp1252', 'UTF-8', $distinctElement));
            });

        return $distinctElements;
    }

    /**
     *
     * @param string $class
     * @param \Chamilo\Libraries\Storage\Parameters\RecordRetrievesParameters $parameters
     * @return \Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet\RecordResultSet
     */
    public function records($class, RecordRetrievesParameters $parameters)
    {
        return new RecordResultSet(
            $this->get_records_result($this->build_records_sql($class, $parameters), $class, $parameters));
    }
}
