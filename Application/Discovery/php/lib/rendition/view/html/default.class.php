<?php
namespace application\discovery;

class HtmlDefaultRendition extends HtmlRendition
{

    function render()
    {
        $html = array();
        return implode("\n", $html);
    }
}
?>