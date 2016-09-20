<?php
namespace Ehb\Libraries\Storage\DataManager\Administration\ResultSet;

use Ehb\Libraries\Storage\DataManager\Administration\DataManager;

/**
 *
 * @package Ehb\Libraries\Storage\DataManager\Administration\ResultSet
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class RecordResultSet extends \Chamilo\Libraries\Storage\DataManager\Doctrine\ResultSet\RecordResultSet
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
