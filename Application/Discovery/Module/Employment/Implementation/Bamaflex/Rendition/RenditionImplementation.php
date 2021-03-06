<?php
namespace Ehb\Application\Discovery\Module\Employment\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_employments()
    {
        return $this->get_module()->get_employments();
    }

    public function get_unique_faculty($parts)
    {
        return $this->get_module()->get_unique_faculty($parts);
    }

    public function get_unique_department($parts)
    {
        return $this->get_module()->get_unique_department($parts);
    }

    public function get_employment_parts($employment_id)
    {
        return $this->get_module()->get_employment_parts($employment_id);
    }
}
