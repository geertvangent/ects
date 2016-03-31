<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component\Group;

use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTable;

/**
 * * ***************************************************************************
 * Table to display the users inside a subscribed platform group.
 * 
 * @author Stijn Van Hoecke
 *         ****************************************************************************
 */
class PlatformGroupRelUserTable extends DataClassTable
{
    const TABLE_IDENTIFIER = Manager :: PARAM_OBJECT_ID;
}
