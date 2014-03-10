<?php
namespace application\discovery\module\profile;

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
     * @return \application\discovery\module\profile\Profile boolean
     */
    public function retrieve_profile($id);
}
