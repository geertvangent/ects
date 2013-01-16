<?php
namespace application\discovery\module\exemption\implementation\bamaflex;

class Module extends \application\discovery\module\exemption\Module
{

    private $cache_exemptions = array();

    function get_exemptions_data($year)
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
?>