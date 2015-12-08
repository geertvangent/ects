<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entity\Group;

use Chamilo\Core\User\Storage\DataClass\User;

/**
 *
 * @package Chamilo\Core\Repository\ContentObject\Assignment\Display\Preview\Table\Entity
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupTable extends \Chamilo\Core\Repository\ContentObject\Assignment\Display\Table\Entity\EntityTable
{
    const TABLE_IDENTIFIER = User :: PROPERTY_ID;
}