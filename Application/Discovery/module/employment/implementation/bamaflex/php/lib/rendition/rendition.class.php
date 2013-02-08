<?php
namespace application\discovery\module\employment\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_employments()
    {
        return $this->get_module()->get_employments();
    }

    function get_unique_faculty($parts)
    {
        return $this->get_module()->get_unique_faculty($parts);
    }

    function get_unique_department($parts)
    {
        return $this->get_module()->get_unique_department($parts);
    }

    function get_employment_parts($employment_id)
    {
        return $this->get_module()->get_employment_parts($employment_id);
    }
}
