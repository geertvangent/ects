<?php
namespace application\discovery\module\career;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     *
     * @param $id
     * @return multitype:\application\discovery\module\enrollment\implementation\bamaflex\Course
     */
    public function retrieve_courses($id);

    /**
     *
     * @param string $user_id
     * @return multitype:\application\discovery\module\career\MarkMoment
     */
    public function retrieve_mark_moments($user_id);
}
