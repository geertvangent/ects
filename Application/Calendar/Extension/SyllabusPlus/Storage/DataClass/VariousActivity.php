<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class VariousActivity extends UserActivity
{

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_syllabus_1617_event_various';
    }
}