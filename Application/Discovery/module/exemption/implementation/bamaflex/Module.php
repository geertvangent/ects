<?php
namespace Chamilo\Application\Discovery\Module\Exemption\Implementation\Bamaflex;

class Module extends \Chamilo\Application\Discovery\Module\Exemption\Module
{

    private $cache_exemptions = array();

    public function get_exemptions_data($year)
    {
        if (! isset($this->cache_exemptions[$year]))
        {
            $exemptions = array();
            foreach ($this->get_exemptions() as $exemption)
            {
                if ($exemption->get_year() == $year)
                {
                    $exemptions[] = $exemption;
                }
            }
            
            $this->cache_exemptions[$year] = $exemptions;
        }
        return $this->cache_exemptions[$year];
    }
}
