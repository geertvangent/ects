<?php
namespace Ehb\Application\Sync;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const ACTION_BROWSE = 'Browser';
    const ACTION_BAMAFLEX = 'Bamaflex';
    const ACTION_ATLANTIS = 'Atlantis';
    const ACTION_CAS = 'Cas';
    const ACTION_DATA = 'Data';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
}
