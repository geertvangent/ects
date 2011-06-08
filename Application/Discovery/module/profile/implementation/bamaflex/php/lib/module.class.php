<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\profile\Module
{

    function render()
    {
        $html = array();
        $html[] = parent :: render();
//        $html[] = '<br />';
//        $html[] = '<br />';
//        $html[] = 'BaMaFlex';

        return implode("\n", $html);
    }
}
?>