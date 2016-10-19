<?php
namespace Ehb\Application\Ects\Ajax;

use Chamilo\Libraries\Architecture\AjaxManager;

/**
 *
 * @package Chamilo\Core\Ects\Ajax
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class Manager extends AjaxManager
{
    const ACTION_FILTER = 'Filter';

    protected function filterProperties($properties, $propertyKeysToMaintain)
    {
        return array_filter(
            $properties,
            function ($key) use ($propertyKeysToMaintain)
            {
                return in_array($key, $propertyKeysToMaintain);
            },
            ARRAY_FILTER_USE_KEY);
    }

    /**
     *
     * @return \Ehb\Application\Ects\Service\EctsService
     */
    protected function getBaMaFlexService()
    {
        return $this->getService('ehb.application.ects.bamaflex');
    }
}
