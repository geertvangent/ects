<?php
namespace application\discovery\module\person;

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
     * @return multitype:\application\discovery\module\person\Person
     */
    public function retrieve_persons($condition, $offset, $count, $order_by);
}
