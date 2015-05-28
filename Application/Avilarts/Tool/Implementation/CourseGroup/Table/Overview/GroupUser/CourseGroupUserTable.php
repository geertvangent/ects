<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Table\Overview\GroupUser;

use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTable;

class CourseGroupUserTable extends DataClassTable
{
    const TABLE_IDENTIFIER = Manager :: PARAM_OBJECT_ID;
}
