<?php
namespace Ehb\Application\Sync\Cas;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;

abstract class Manager extends Application
{
    const ACTION_BROWSE = 'Browser';
    const ACTION_ALL_USERS = 'AllUsers';
    const ACTION_STATISTICS = 'Statistics';
    const DEFAULT_ACTION = self::ACTION_BROWSE;
    const PARAM_ACTION = 'cas_action';

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $user
     * @param string $parent
     */
    public function __construct(ApplicationConfigurationInterface $applicationConfiguration)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent::__construct($applicationConfiguration);
    }
}
