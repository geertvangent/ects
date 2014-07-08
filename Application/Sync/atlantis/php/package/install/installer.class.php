<?php
namespace application\ehb_sync\atlantis;

/**
 *
 * @author Hans De Bisschop
 */
class Installer extends \common\libraries\package\Installer
{

    /**
     * Constructor
     */
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }
}
