<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\CourseGroup;

use Chamilo\Application\Weblcms\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\Group\GroupTable;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Table\Entry\CourseGroup
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CourseGroupTable extends GroupTable
{
    const TABLE_IDENTIFIER = CourseGroup::PROPERTY_ID;
}