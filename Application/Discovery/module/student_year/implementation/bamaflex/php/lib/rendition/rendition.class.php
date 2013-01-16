<?php
namespace application\discovery\module\student_year\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_student_years()
    {
        return $this->get_module()->get_student_years();
    }
}
?>