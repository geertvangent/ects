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
class FilterTrainingTypesComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
{
    // Parameters
    const PARAM_YEAR = 'year';
    const PARAM_FACULTY = 'faculty';

    // Properties
    const PROPERTY_TYPE = 'type';

    /**
     *
     * @var string
     */
    private $currentYear;

    /**
     *
     * @var string
     */
    private $currentFacultyIdentifier;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array(self::PARAM_YEAR);
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $jsonAjaxResult = new JsonAjaxResult();
        $jsonAjaxResult->set_properties(array(self::PROPERTY_TYPE => $this->getTypes()));
        $jsonAjaxResult->display();
    }

    /**
     *
     * @return string
     */
    private function getCurrentYear()
    {
        if (! isset($this->currentYear))
        {
            $this->currentYear = $this->getRequestedPostDataValue(self::PARAM_YEAR);
        }

        return $this->currentYear;
    }

    /**
     *
     * @return string
     */
    private function getCurrentFacultyIdentifier()
    {
        if (! isset($this->currentFacultyIdentifier))
        {
            $this->currentFacultyIdentifier = $this->getRequestedPostDataValue(self::PARAM_FACULTY);
        }

        return $this->currentFacultyIdentifier;
    }

    /**
     *
     * @return string[]
     */
    private function getTypes()
    {
        return $this->getEctsService()->getTypesForYearAndFacultyIdentifier(
            $this->getCurrentYear(),
            $this->getCurrentFacultyIdentifier());
    }
}
