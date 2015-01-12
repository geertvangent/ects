<?php
namespace Chamilo\Application\Discovery\Module\Advice\Implementation\Bamaflex;

class Module extends \Chamilo\Application\Discovery\Module\Advice\Module
{

    private $cache_advices = array();

    public function get_advices_data($enrollment)
    {
        if (! isset($this->cache_advices[$enrollment->get_id()]))
        {
            $advices = array();
            foreach ($this->get_advices() as $advice)
            {
                if ($advice->get_enrollment_id() == $enrollment->get_id())
                {
                    $advices[] = $advice;
                }
            }
            
            $this->cache_advices[$enrollment->get_id()] = $advices;
        }
        return $this->cache_advices[$enrollment->get_id()];
    }

    public function has_advices($enrollment = null)
    {
        if ($enrollment)
        {
            return count($this->get_advices_data($enrollment));
        }
        else
        {
            return count($this->get_advices()) > 0;
        }
    }
}