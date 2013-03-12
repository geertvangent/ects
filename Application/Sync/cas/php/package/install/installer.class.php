<?php
namespace application\ehb_sync\cas;

/**
 *
 * @author Hans De Bisschop
 */
class Installer extends \common\libraries\package\Installer
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }
}
