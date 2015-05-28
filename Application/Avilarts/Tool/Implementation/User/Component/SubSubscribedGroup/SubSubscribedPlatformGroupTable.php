<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component\SubSubscribedGroup;

use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTable;

/**
 * * ***************************************************************************
 * Table to display a list of subgroups subscribed to a course.
 * 
 * @author Stijn Van Hoecke
 *         ****************************************************************************
 */
class SubSubscribedPlatformGroupTable extends DataClassTable
{
    const TABLE_IDENTIFIER = Manager :: PARAM_OBJECT_ID;
}
