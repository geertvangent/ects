<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Repository\EctsRepository;
use Ehb\Application\Ects\Service\EctsService;
use Ehb\Application\Ects\Storage\DataClass\Training;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FilterComponent extends \Ehb\Application\Ects\Ajax\Manager
{
    // Parameters
    const PARAM_YEAR = 'year';
    const PARAM_FACULTY = 'faculty';
    const PARAM_TYPE = 'type';
    const PARAM_TEXT = 'text';

    // Properties
    const PROPERTY_FILTER = 'filter';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_TEXT = 'text';

    private $ectsService;

    private $currentYear;

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

        $jsonAjaxResult->set_properties(
            array(
                self::PROPERTY_FILTER => array(
                    self::PROPERTY_YEAR => $this->getCurrentYear(),
                    self::PROPERTY_FACULTY => $this->getCurrentFaculty(),
                    self::PROPERTY_TYPE => $this->getCurrentType()),
                self::PROPERTY_YEAR => $this->getYears(),
                self::PROPERTY_FACULTY => $this->getFaculties(),
                self::PROPERTY_TYPE => $this->getTypes()));

        $jsonAjaxResult->display();
    }

    /**
     *
     * @return \Ehb\Application\Ects\Service\EctsService
     */
    private function getEctsService()
    {
        if (! isset($this->ectsService))
        {
            $this->ectsService = new EctsService(new EctsRepository());
        }

        return $this->ectsService;
    }

    public function getPostedYear()
    {
        return $this->getRequest()->query->get(self::PARAM_YEAR);
        return $this->getRequest()->request->get(self::PARAM_YEAR);
    }

    public function getPostedFaculty()
    {
        return $this->getRequest()->query->get(self::PARAM_FACULTY);
        return $this->getRequest()->request->get(self::PARAM_FACULTY);
    }

    public function getPostedType()
    {
        return $this->getRequest()->query->get(self::PARAM_TYPE);
        return $this->getRequest()->request->get(self::PARAM_TYPE);
    }

    public function getCurrentYear()
    {
        if (! isset($this->currentYear))
        {
            $this->currentYear = $this->getPostedYear();

            if (is_null($this->currentYear))
            {
                $this->currentYear = array_shift($this->getEctsService()->getYears());
            }
        }

        return $this->currentYear;
    }

    public function getCurrentFaculty()
    {
        if (! isset($this->currentFaculty))
        {
            $this->currentFaculty = $this->getPostedFaculty();

            if (is_null($this->currentFaculty))
            {
                $this->currentFaculty = array_shift(
                    $this->getEctsService()->getFacultiesForYear($this->getCurrentYear()));
            }
        }

        return $this->currentFaculty;
    }

    public function getCurrentType()
    {
        if (! isset($this->currentType))
        {
            $this->currentType = $this->getPostedType();

            if (is_null($this->currentType))
            {
                $this->currentType = array_shift($this->getEctsService()->getTypesForYear($this->getCurrentYear()));
            }
        }

        return $this->currentType;
    }

    public function getYears()
    {
        return $this->getEctsService()->getYears();
    }

    public function getFaculties()
    {
        $faculties = $this->getEctsService()->getFacultiesForYear($this->getCurrentYear());
        $formattedFaculties = array();

        foreach ($faculties as $faculty)
        {
            $formattedFaculties[$faculty[Training::PROPERTY_FACULTY_ID]] = $faculty[Training::PROPERTY_FACULTY];
        }

        return $formattedFaculties;
    }

    public function getTypes()
    {
        $types = $this->getEctsService()->getTypesForYear($this->getCurrentYear());
        $formattedTypes = array();

        foreach ($types as $type)
        {
            $formattedTypes[$type[Training::PROPERTY_TYPE_ID]] = $type[Training::PROPERTY_TYPE];
        }

        return $formattedTypes;
    }
}
