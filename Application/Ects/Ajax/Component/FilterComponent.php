<?php
namespace Ehb\Application\Ects\Ajax\Component;

use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Ehb\Application\Ects\Storage\DataClass\Training;

/**
 *
 * @package Chamilo\Core\Ects\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FilterComponent extends \Ehb\Application\Ects\Ajax\Manager implements NoAuthenticationSupport
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
    const PROPERTY_TRAINING = 'training';

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
     * @var string
     */
    private $currentTypeIdentifier;

    /**
     *
     * @var string
     */
    private $currentText;

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
                    self::PROPERTY_FACULTY => $this->getCurrentFacultyIdentifier(),
                    self::PROPERTY_TYPE => $this->getCurrentTypeIdentifier(),
                    self::PROPERTY_TEXT => $this->getCurrentText()),
                self::PROPERTY_YEAR => $this->getYears(),
                self::PROPERTY_FACULTY => $this->getFaculties(),
                self::PROPERTY_TYPE => $this->getTypes(),
                self::PROPERTY_TRAINING => $this->getTrainings()));

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

            if (empty($this->currentYear))
            {
                $this->currentYear = array_shift($this->getEctsService()->getYears());
            }
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
     * @return string
     */
    private function getCurrentTypeIdentifier()
    {
        if (! isset($this->currentTypeIdentifier))
        {
            $this->currentTypeIdentifier = $this->getRequestedPostDataValue(self::PARAM_TYPE);
        }

        return $this->currentTypeIdentifier;
    }

    /**
     *
     * @return string
     */
    private function getCurrentText()
    {
        if (! isset($this->currentText))
        {
            $this->currentText = $this->getRequestedPostDataValue(self::PARAM_TEXT);
        }

        return $this->currentText;
    }

    /**
     *
     * @return string[]
     */
    private function getYears()
    {
        return $this->getEctsService()->getYears();
    }

    /**
     *
     * @return string[]
     */
    private function getFaculties()
    {
        $faculties = $this->getEctsService()->getFacultiesForYear($this->getCurrentYear());
        $formattedFaculties = array();

        foreach ($faculties as $faculty)
        {
            $formattedFaculties[$faculty[Training::PROPERTY_FACULTY_ID]] = $faculty[Training::PROPERTY_FACULTY];
        }

        return $formattedFaculties;
    }

    /**
     *
     * @return string[]
     */
    private function getTypes()
    {
        $types = $this->getEctsService()->getTypesForYearAndFacultyIdentifier(
            $this->getCurrentYear(),
            $this->getCurrentFacultyIdentifier());
        $formattedTypes = array();

        foreach ($types as $type)
        {
            $formattedTypes[$type[Training::PROPERTY_TYPE_ID]] = $type[Training::PROPERTY_TYPE];
        }

        return $formattedTypes;
    }

    /**
     *
     * @return string[]
     */
    private function getTrainings()
    {
        $trainings = $this->getEctsService()->getTrainingsForYearFacultyIdentifierTypeIdentifierAndText(
            $this->getCurrentYear(),
            $this->getCurrentFacultyIdentifier(),
            $this->getCurrentTypeIdentifier(),
            $this->getCurrentText());

        return $trainings;
    }
}
