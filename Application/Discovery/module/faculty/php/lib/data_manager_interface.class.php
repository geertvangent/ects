<?php
namespace application\discovery\module\faculty;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     *
     * @param int $id
     * @return multitype:\application\discovery\module\faculty\Faculty
     */
    public function retrieve_faculties($year);
}
