<?php
namespace application\discovery\module\advice\implementation\bamaflex;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

class Module extends \application\discovery\module\advice\Module
{

    private $cache_advices = array();

    function get_advices_data($enrollment)
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

    function has_advices($enrollment = null)
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
