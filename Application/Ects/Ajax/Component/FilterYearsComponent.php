<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FilterYearsComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    
    // Properties
    const PROPERTY_YEAR = 'year';

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array();
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $jsonAjaxResult = new JsonAjaxResult();
        $jsonAjaxResult->set_properties(array(self::PROPERTY_YEAR => $this->getYears()));
        $jsonAjaxResult->display();
    }

    /**
     *
     * @return string[]
     */
    private function getYears()
    {
        return $this->getBaMaFlexService()->getYears();
    }
}
