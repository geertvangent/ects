<?php
namespace Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Event;

use Chamilo\Core\Tracking\Storage\DataClass\Event;

/**
 *
 * @package Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Event
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Search extends Event
{

    /**
     *
     * @return multitype:string
     */
    public function getTrackerClasses()
    {
        return array(\Ehb\Application\Ects\Integration\Chamilo\Core\Tracking\Storage\DataClass\Search::class_name());
    }
}