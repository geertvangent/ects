<?php
namespace Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet;

use Ehb\Libraries\Storage\DataManager\Administration\DataManager;

/**
 *
 * @package Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet$DataClassResultSet
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class DataClassResultSet extends \Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet\DataClassResultSet
{

    /**
     *
     * @see \Chamilo\Libraries\Storage\ResultSet\ResultSet::process_record()
     */
    public function process_record($record)
    {
        return DataManager::process_record($record);
    }
}
