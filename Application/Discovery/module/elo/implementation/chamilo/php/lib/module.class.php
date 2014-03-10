<?php
namespace application\discovery\module\elo\implementation\chamilo;

use application\discovery\Parameters;

class Module extends \application\discovery\module\elo\Module
{

    public static function module_parameters()
    {
        return new Parameters();
    }
}
