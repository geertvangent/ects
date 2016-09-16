<?php
namespace Ehb\Libraries\Storage\DataManager\Administration;

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
}
