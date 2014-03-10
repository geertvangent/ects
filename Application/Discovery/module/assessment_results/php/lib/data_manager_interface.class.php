<?php
namespace application\discovery\module\assessment_results;

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
     * @return multitype:\application\discovery\module\assessment_results\Person
     */
    public function retrieve_persons($condition, $offset, $count, $order_by);
}
