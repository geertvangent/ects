<?php
namespace Ehb\Core\Metadata\Provider;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 * This interface provides the class structure to provide and renders the properties to link to a metadata element
 *
 * @package Ehb\Core\Metadata\Provider$PropertyProviderInterface
 * @author Sven Vanpoucke - Hogeschool Gent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
interface PropertyProviderInterface
{

    /**
     * Returns the properties that can be linked to the metadata elements
     *
     * @return string[]
     */
    public function getAvailableProperties();

    /**
     * Renders a property for a given entity
     *
     * @param string $property
     * @param DataClass $entity
     *
     * @return string
     */
    public function renderProperty($property, DataClass $entity);
}