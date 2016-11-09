<?php
namespace Ehb\Application\Atlantis\Role\Entity;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'role_entity_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_DELETE = 'Deleter';
    const ACTION_CREATE = 'Creator';
    const ACTION_ENTITY_TYPE = 'EntityType';
    const DEFAULT_ACTION = self::ACTION_CREATE;
    const PARAM_ROLE_ENTITY_ID = 'role_entity_id';
    const PARAM_ENTITY_TYPE = 'entity_type';
    const PARAM_ENTITY_ID = 'entity_id';
    const PARAM_START_DATE = 'start_date';
    const PARAM_END_DATE = 'end_date';
}
