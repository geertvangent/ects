<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use application\discovery\module\teaching_assignment\DataManager;

class Module extends \application\discovery\module\teaching_assignment\Module
{

    private $cache_teaching_assignments = array();

    public function get_teaching_assignments_data($parameters)
    {
        $year = $parameters->get_year();
        $user_id = $parameters->get_user_id();

        if (! isset($this->cache_teaching_assignments[$user_id][$year]))
        {
            foreach ($this->get_teaching_assignments($parameters) as $teaching_assignment)
            {
                $this->cache_teaching_assignments[$user_id][$year][] = $teaching_assignment;
            }
        }
        return $this->cache_teaching_assignments[$user_id][$year];
    }

    public function get_years()
    {
        if (! isset($this->years))
        {
            $this->years = DataManager :: get_instance($this->get_module_instance())->retrieve_years(
                    $this->get_module_parameters());
        }
        return $this->years;
    }
}
