<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Year1617;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class VariousActivity extends \Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\VariousActivity
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