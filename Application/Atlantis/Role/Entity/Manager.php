<?php
namespace Ehb\Application\Atlantis\Role\Entity;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'role_entity_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const ACTION_CREATE = 'creator';
    const ACTION_ENTITY_TYPE = 'entity_type';
    const DEFAULT_ACTION = self :: ACTION_CREATE;
    const PARAM_ROLE_ENTITY_ID = 'role_entity_id';
    const PARAM_ENTITY_TYPE = 'entity_type';
    const PARAM_ENTITY_ID = 'entity_id';
    const PARAM_START_DATE = 'start_date';
    const PARAM_END_DATE = 'end_date';
}
