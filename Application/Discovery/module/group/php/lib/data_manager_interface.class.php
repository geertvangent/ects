<?php
namespace application\discovery\module\group;

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
     * @return multitype:\application\discovery\module\core\group\Group
     */
    public function retrieve_groups($training_id);
}
