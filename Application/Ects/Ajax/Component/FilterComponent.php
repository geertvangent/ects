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
                    self::PROPERTY_FACULTY => $this->getCurrentFaculty(),
                    self::PROPERTY_TYPE => $this->getCurrentType(),
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
                $this->currentYear = array_shift($this->getBaMaFlexService()->getYears());
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
        return $this->getBaMaFlexService()->getYears();
    }

    /**
     *
     * @return string[]
     */
    private function getFaculties()
    {
        return $this->getBaMaFlexService()->getFacultiesForYear($this->getCurrentYear());
    }

    /**
     *
     * @return string[]
     */
    private function getTypes()
    {
        return $this->getBaMaFlexService()->getTypesForYearAndFacultyIdentifier(
            $this->getCurrentYear(),
            $this->getCurrentFacultyIdentifier());
    }

    /**
     *
     * @return string[]
     */
    private function getTrainings()
    {
        $trainings = $this->getBaMaFlexService()->getTrainingsForYearFacultyIdentifierTypeIdentifierAndText(
            $this->getCurrentYear(),
            $this->getCurrentFacultyIdentifier(),
            $this->getCurrentTypeIdentifier(),
            $this->getCurrentText());

        return $trainings;
    }

    /**
     *
     * @return string[]
     */
    private function getCurrentFaculty()
    {
        foreach ($this->getFaculties() as $faculty)
        {
            if ($faculty[Training::PROPERTY_FACULTY_ID] == $this->getCurrentFacultyIdentifier())
            {
                return $faculty;
            }
        }

        return null;
    }

    /**
     *
     * @return string[]
     */
    private function getCurrentType()
    {
        foreach ($this->getTypes() as $type)
        {
            if ($type[Training::PROPERTY_TYPE_ID] == $this->getCurrentTypeIdentifier())
            {
                return $type;
            }
        }

        return null;
    }
}
