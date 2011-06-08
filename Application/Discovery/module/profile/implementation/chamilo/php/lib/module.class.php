<?php
namespace application\discovery\module\profile\implementation\chamilo;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\profile\Module
{

    function render()
    {
        $html = array();
        $html[] = parent :: render();
//        $html[] = '<br />';
//        $html[] = '<br />';
//        $html[] = 'Chamilo';

        return implode("\n", $html);
    }
}
?>