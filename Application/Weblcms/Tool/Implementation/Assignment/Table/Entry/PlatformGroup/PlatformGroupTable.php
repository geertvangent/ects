<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\PlatformGroup;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTable;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\PlatformGroup
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PlatformGroupTable extends GroupTable
{
    const TABLE_IDENTIFIER = Group::PROPERTY_ID;
}